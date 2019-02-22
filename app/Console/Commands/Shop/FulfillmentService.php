<?php

namespace App\Console\Commands\Shop;

use App\Console\Commands\AbstractCommand;
use App\Manager\Basic\Status;
use App\Model\Cron;
use App\Manager\Shopify\FulfillmentService as ShopifyFulfillmentService;
use App\Model\Location;

class FulfillmentService extends AbstractCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:fulfillment_service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Shopify Fulfillment Service';

    /**
     * FulfillmentService constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crons = Cron::where( [
            ['status' , '=' , Cron::QUEUED ],
            [ 'type' , '=' , Cron::TYPE_FULFILLMENT_SERVICE ]
        ] )->get();

        if( ! isset( $crons ) ) {
            return false;
        }

        foreach( $crons as $cron ){

            $this->shop = $cron->shopOwner->shop ;

            $location = new \App\Manager\Shopify\Location();
            $location->setShop( $cron->shopOwner->name )
                ->setAccessToken( $cron->shopOwner->token ) ;

            $response = $location->all();

            $locations = [];
            if (is_array($response) && !empty($response)){
                foreach($response as $key => $value){
                    $entity = new Location();
                    $locations[] = $entity->load($value);
                }
            }

            if( ! is_array( $locations ) || empty( $locations ) ) {
                continue;
            }

            /** @var \App\Manager\Shopify\Location $shopifyLocation */
            foreach( $locations as $shopifyLocation ){
                $dataBurstLocation = Location::where( 'id' , $shopifyLocation->getId() )->first();

                if( ! isset( $dataBurstLocation ) ) {
                    $dataBurstLocation = new Location();
                }

                $dataBurstLocation->id   = $shopifyLocation->getId();
                $dataBurstLocation->meta  = json_encode($shopifyLocation ) ;
                $dataBurstLocation->shop_owner_id = $cron->shopOwner->id;

                $dataBurstLocation->save();
            }

            $fulfillmentService = new ShopifyFulfillmentService();

            $fulfillmentService->setShop( $cron->shopOwner->name )
                ->setAccessToken( $cron->shopOwner->token ) ;

            $fulfillmentService->setName( env( 'APP_NAME' ) )
                ->setCallbackUrl( env( 'FULFILLMENT_SERVICE_REDIRECT' ) )
                ->setInventoryManagement( false )
                ->setTrackingSupport( false )
                ->setRequiresShippingMethod( false )
                ->setFormat( ShopifyFulfillmentService::JSON_FORMAT )
                ->save();

            $status  = Cron::ERROR ;
            $results = is_string($fulfillmentService->getResults())
                ? json_decode($fulfillmentService->getResults())
                : $fulfillmentService->getResults();

            switch( $fulfillmentService->httpCode ){

                case 201: case 200:
                $location = Location::where(
                    'id' ,
                    $results->{ $fulfillmentService->getSingularName() }->location_id
                )->first();

                if( ! isset( $location ) ) {
                    $location = new Location();
                }

                $location->id   = $results
                    ->{ $fulfillmentService->getSingularName() }
                    ->location_id;
                $location->meta  = json_encode( $results ) ;
                $location->shop_owner_id = $cron->shopOwner->id;

                $message                 = 'Fulfillment Service created in Shopify';

                if( $location->save() ) {

                    $status   = Cron::COMPLETE ;
                    $message .= " and saved. " ;
                }
                else {
                    $message .= " but did not save. " ;
                }

                $this->info($message);

                break;
                default:
                    $this->info( $fulfillmentService->httpCode . ' Error: ' . json_encode( $fulfillmentService->getResults() ) ) ;
                    break;

            }

            if( $status === Status::COMPLETE ){
                $cron->delete();
            }
            else {
                $cron->status = $status;
                $cron->save();
            }

        }
    }
}