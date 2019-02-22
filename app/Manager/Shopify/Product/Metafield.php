<?php

namespace App\Manager\Shopify\Product;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Shopify\AbstractMetafield;

class Metafield extends AbstractMetafield
{

    public function getSingularName()
    {
       return 'metafield';
    }

    public function getPluralName()
    {
       return 'metafields';
    }

    public function url()
    {
        if( strlen( $this->getShop() ) === 0 || strlen( $this->getOwnerId() ) === 0 ) return '';

        return $this->restAdminUri() . 'products' . DIRECTORY_SEPARATOR . $this->getOwnerId() . DIRECTORY_SEPARATOR . $this->getPluralName() . '.json';
    }

    public function update( $id  ){
        if( strlen( $this->getShop() ) === 0 || strlen( $this->getOwnerId() ) === 0 ||  strlen( $this->getId() ) === 0) return '';
        $url = $this->restAdminUri() . 'products' . DIRECTORY_SEPARATOR . $this->getOwnerId()
            . DIRECTORY_SEPARATOR . $this->getPluralName() . DIRECTORY_SEPARATOR . $this->getId() . '.json';

        $httpConnect    = new Client();
        $this->owner_id = null;
        $response       = $httpConnect->request( $url , [ $this->getSingularName() => $this ] , 'PUT', $this->headers() );

        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $httpCode = $httpConnect->http_code;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response , 'errors' ) || ( $httpCode <= 599 && $httpCode >= 400 ) ){
            $this->addError( $response );
            return false;
        }
        else{
            if(Assist::getProperty( $response , $this->getSingularName()  ) ){
                if( Assist::getProperty( $response->{ $this->getSingularName() }  , 'id' ) ){
                    return $response->{ $this->getSingularName() }->id;
                }
            }
            return false;
        }
    }

    public function fetch( $page = 1 , $limit = 250 )
    {
        $url            = $this->url() . "?page=$page&limit=$limit" ;
        $httpConnect    = new Client();
        $response       = $httpConnect->request( $url ,[], 'GET' , $this->headers() ) ;
        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response  , $this->getPluralName() ) ) {
            return $response->{ $this->getPluralName() };
        }

        return null;

    }

    public function remove(){

        $uri = $this->restAdminUri() . 'products' .  DIRECTORY_SEPARATOR . $this->getOwnerId() .
            DIRECTORY_SEPARATOR . $this->getPluralName() . DIRECTORY_SEPARATOR . $this->getId() . '.json';

        $httpConnect = new Client();
        $response    = $httpConnect->request( $uri ,[], 'DELETE' , $this->headers() ) ;
        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response,'errors')) {
            $this->addError( $response->errors );
            return $response->errors;
        }
        $response =  !is_null( Assist::getProperty($response, $this->getPluralName())) ? $response->{$this->getPluralName()} : $response;
        return $response ;
    }
}