<?php

namespace App\Http\Controllers;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Logger;
use App\Manager\Basic\Status;
use App\Manager\Shopify\InventoryLevel;
use App\Model\Cron;
use App\Model\Configuration;
use App\Model\Location;
use App\Model\Product;
use App\Manager\Shopify\Auth;
use App\Manager\Shopify\Shop;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ShopifyController extends Controller
{
    /**
     * Shopify Data
     */
    protected $data;

    /**
     * @var \App\Model\ShopOwner $shopOwner
     */
    protected $shopOwner;

    /**
     * Installs the DataBurst App To Shopify Admin
     *
     * @param Request $request
     * @return mixed
     */
    public function install( Request $request ){

        $shop  = $request->input( 'shop' ) ;
        $token = Auth::getAccessToken( $shop ) ;

        $shopifyShop = new Shop();
        $shopifyShop->setShop( $shop )->setAccessToken( $token );
        /** @var Shop $results */
        $results =  $shopifyShop->fetch();

        $timezone = $results->getIanaTimezone();
        $email    = $results->getEmail();
        $id       = $results->getId();

        // save shop owner to database
        $shopOwner               = new \App\Model\ShopOwner();
        $shopOwner->id           = $id;
        $shopOwner->name         = $shop;
        $shopOwner->timezone     = $timezone;
        $shopOwner->token        = $token;
        $shopOwner->email        = $email;
        $shopOwner->status       = Status::PENDING;
        $shopOwner->meta = json_encode( $results ) ;

        $shopOwner->save();

        // save cron processes to database
        Cron::init($shopOwner);

        // save initial configuration
        Configuration::init($shopOwner);

        $shopifyAppName = env( 'APP_HANDLE' );

        return redirect( 'https://'
            . $shop . '/admin/apps/'
            . $shopifyAppName . '?shop='
            . $shop,
            302,
            [],
            true);

    }

    /**
     * Handles Shopify Webhooks
     *
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function webhooks(){

        $headers = $this->getFromHeaders();
        $data    = trim( file_get_contents('php://input') );
        $topic   = $headers[ 'topic' ];
        $hmac    = $headers[ 'hmac' ];
        $shop    = $headers[ 'shop' ];

        if( strlen( $shop ) === 0 ) {
            return response( [] , 200 );
        }

        $verified = $this->verify( $data , $hmac ) ;

        if( $verified == false ) {
            return response( 'Not Allowed' , 200 );
        }

        $shopOwner = new \App\Model\ShopOwner();
        $shopOwner = $shopOwner->where( 'name' , $shop )->first();

         if( ! isset( $shopOwner ) ) {
             return response( [] , 200 ) ;
         }

        $this->shopOwner = $shopOwner ;
        $this->data = json_decode( $data );
        switch( $topic ){
            case 'locations/create':case 'locations/update':
                $shopify        = new \App\Manager\Shopify\Location();
                $shopifyLocation = $shopify->load($this->data);
                $eloquent       = $shopifyLocation->toEloquent($this->shopOwner->id);

                $eloquent->save();
                break;
            case 'locations/delete':
                $shopify        = new \App\Manager\Shopify\Location();
                $shopifyLocation = $shopify->load($this->data);
                $eloquent       = $shopifyLocation->toEloquent($this->shopOwner->id);
                try{
                    $eloquent->delete();
                } catch (QueryException $queryException){

                } catch (\Exception $exception){

                }
                break;
            case 'products/create':case 'products/update':
                $shopify        = new \App\Manager\Shopify\Product();
                $shopifyProduct = $shopify->load($this->data);
                $eloquent       = $shopifyProduct->toEloquent($this->shopOwner->id);

                $eloquent->save();

                if( ! empty( $shopify->getVariants() ) ){
                    foreach ($shopify->getVariants() as $variant) {
                        $elo = $variant->toEloquent($this->shopOwner->id);
                        $elo->save();
                    }
                }
                break;
            case 'products/delete':
                $shopify        = new \App\Manager\Shopify\Product();
                $shopifyProduct = $shopify->load($this->data);
                $eloquent       = $shopifyProduct->toEloquent($this->shopOwner->id);
                $eloquent->status = Status::ARCHIVE;
                $eloquent->save();
                if( isset( $eloquent->variants ) ){
                    $results[] = $eloquent->variants()->update([
                        'status'     => Status::ARCHIVE,
                        'id'         => null,
                        'product_id' => null,
                        'meta'       => null
                    ]);
                }
                break;
            case 'inventory_levels/update':case 'inventory_levels/connect':
                $shopify = new InventoryLevel();
                $shopifyInv = $shopify->load($this->data);
                $eloquent = $shopifyInv->toEloquent($this->shopOwner->id);
                $eloquent->status = Status::CONNECTED;
                $eloquent->save();
                break;
            case 'inventory_levels/disconnect':
                $shopify         = new InventoryLevel();
                $shopifyLocation = $shopify->load($this->data);
                $eloquent        = $shopifyLocation->toEloquent($this->shopOwner->id);
                $eloquent->status = Status::DISCONNECTED;
                $eloquent->save();
                break;
            case 'shop/redact':case 'app/uninstalled': case 'customer/redact':
                try{
                    $this->uninstallApp();
                }
                catch ( \Exception $exception ){
                    Logger::writeToLogFile(
                        "Error Uninstalling App: " . $exception->getMessage() ,
                        'shopify',
                        $this->shopOwner->shop
                    );
                }
                break;
        }

          return response( [] , 200 );
    }

    /**
     * @throws \Exception
     */
    private function uninstallApp(){
        try {
            $this->shopOwner->delete();

            // todo remove shop log folder
            // todo remove shop exports folder
            // todo remove shop imports folder


        }
        catch ( QueryException $queryException ){
            Logger::writeToLogFile(
                "DB ERROR: " . $queryException->getMessage() . " [ TRACE ] " . $queryException->getTraceAsString(),
                'webhook',
                $this->shopOwner->shop
            );
        }
        catch ( \Exception $exception ){
            Logger::writeToLogFile(
                "DB ERROR: " . $exception->getMessage() . " [ TRACE ] " . $exception->getTraceAsString(),
                'webhook',
                $this->shopOwner->shop
            );
        }
    }

    /**
     * @param $data
     * @param $hmac_header
     * @return bool
     */
    private function verify($data, $hmac_header)
    {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, env('SHOPIFY_SECRET' ), true));
        return ($hmac_header == $calculated_hmac);
    }

}