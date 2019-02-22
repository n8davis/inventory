<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $limit = 50;
    /**
     * @var \App\Model\ShopOwner $shopOwner
     */
    protected $shopOwner;

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * Display items based on date found in this column
     */
    protected $dateColumn = 'created_at';

    private $topic;
    private $hmac;
    private $shop;
    private $has_hmac;

    /**
     * Controller constructor.
     * @param Request $request
     */
    public function __construct( Request $request )
    {
        $this->request = $request;
        $this->hmacPresent() ;

        $shop      = $this->shopInHeader();
        $shopOwner = new \App\Model\ShopOwner();
        $shopOwner = $shopOwner->where('name', $shop)->first();
        if( isset( $shopOwner ) || $this->has_hmac ) {
            $this->shopOwner = $shopOwner;
        } else {
            abort( 422  ) ;
        }

    }

    private function hmacPresent()
    {

        $this->getFromHeaders() ;

        if( ( array_key_exists( 'REDIRECT_QUERY_STRING' , $_SERVER ) && strpos( $_SERVER[ 'REDIRECT_QUERY_STRING' ] , 'hmac' ) !== false )
            || ( array_key_exists( 'QUERY_STRING' , $_SERVER ) && strpos( $_SERVER[ 'QUERY_STRING' ] , 'hmac' ) !== false )
            || strlen( $this->hmac ) > 0
        ){
            $this->has_hmac = true;
            return true;
        }

        return false;
    }

    /**
     * Looks for APP Header to set ShopOwner Object
     *
     * @return bool|null
     */
    public function shopInHeader()
    {
        $shop = $this->request->input('shop');
        return $shop;
    }

    protected function getFromHeaders(){
        $hmac  = '';
        $shop  = '';
        $topic = '';

        foreach ( getallheaders() as $name => $value) {

            if ( strtolower( $name ) == 'x-shopify-shop-domain') {
                $shop = trim($value);
            }
            if ( strtolower( $name ) == 'x-shopify-topic') {
                $topic = trim($value);
            }
            if ( strtolower( $name ) == 'x-shopify-hmac-sha256') {

                $this->has_hmac = true;
                $hmac           = trim($value);

            }
        }

        $this->shop  = $shop ;
        $this->hmac  = $hmac ;
        $this->topic = $topic;

        return [
            'shop'  => $shop,
            'topic' => $topic,
            'hmac'  => $hmac
        ];
    }
    protected function page()
    {
        return $this->request->input( 'page' ) ?? 1 ;
    }
}
