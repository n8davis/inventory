<?php

namespace App\Manager\Shopify\Product\Variant;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Shopify\AbstractMetafield;

/**
 * Class Metafield
 * @package App\Manager\Shopify\Product\Variant
 */
class Metafield extends AbstractMetafield
{

    protected $product_id ;
    protected $variant_id ;

    public function create()
    {
        $httpConnect = new Client();

        $response = $httpConnect->request( $this->url() ,[ $this->getSingularName() => $this ], 'POST', $this->headers() );

        Assist::consoleLog( "RESPONSE: $response" );
        $this->setResults( $response ) ;
        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        if( Assist::getProperty( $response , 'errors' ) || ( $this->httpCode <= 599 && $this->httpCode >= 400 ) ){
            $this->addError( $response->errors ) ;
        }

        return $response;
    }


    public function update( $id  ){
        $url = $this->restAdminUri() . 'products' . DIRECTORY_SEPARATOR .
            $this->getProductId() . DIRECTORY_SEPARATOR . 'variants'. DIRECTORY_SEPARATOR . $this->getVariantId() .
            DIRECTORY_SEPARATOR . 'metafields' . DIRECTORY_SEPARATOR . $id . '.json' ;

        $httpConnect    = new Client();
        $this->product_id = null;
        $this->owner_id = null;
        $this->owner_resource = null;
        $this->variant_id = null;
        $this->key = null;
        $this->namespace = null;
        $response       = $httpConnect->request( $url , [ $this->getSingularName() => $this ] , 'PUT', $this->headers() );
        $this->httpCode = $httpConnect->getHttpCode() ;
        $this->setResults( $response ) ;

        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $httpCode = $httpConnect->http_code;

        if( Assist::getProperty( $response , 'errors' ) || ( $httpCode <= 599 && $httpCode >= 400 ) ){
            $this->addError( $response );
            return $response;
        }
        else{
            if(Assist::getProperty( $response , $this->getSingularName()  ) ){
                if( Assist::getProperty( $response->{ $this->getSingularName() }  , 'id' ) ){
                    return $response->{ $this->getSingularName() }->id;
                }
            }
            return $response;
        }
    }

    /**
     * @return string
     */
    public function getSingularName()
    {
        return 'metafield';
    }

    /**
     * @return string
     */
    public function getPluralName()
    {
        return 'metafields';
    }

    /**
     * @return string
     */
    public function url()
    {
        if (strlen($this->getShop()) === 0 || strlen($this->getProductId()) === 0 || strlen( $this->getVariantId() ) === 0 ) return '';

        return $this->restAdminUri() . 'products' . DIRECTORY_SEPARATOR . $this->getProductId() . DIRECTORY_SEPARATOR .
            'variants' . DIRECTORY_SEPARATOR . $this->getVariantId() . DIRECTORY_SEPARATOR . 'metafields.json';
    }

    /**
     * @param int $page
     * @param int $limit
     * @return null
     */
    public function fetch( $page = 1 , $limit = 250 )
    {
        $url            = $this->url() . "?page=$page&limit=$limit";
        $httpConnect    = new Client();
        $response       = $httpConnect->request( $url , [], 'GET', $this->headers());
        $this->httpCode = $httpConnect->getHttpCode();
        $response       = is_string($response) ? json_decode($response) : $response;

        $this->setResults($response);

        if (Assist::getProperty($response, $this->getPluralName())) {
            return $response->{$this->getPluralName()};
        }

        return null;

    }

    /**
     * @return mixed
     */
    public function remove()
    {

        $uri =  $this->restAdminUri() . 'products' . DIRECTORY_SEPARATOR . $this->getProductId() . DIRECTORY_SEPARATOR .
            'variants' . DIRECTORY_SEPARATOR . $this->getVariantId() . DIRECTORY_SEPARATOR . 'metafields' .
            DIRECTORY_SEPARATOR . $this->getId() . '.json';

        $httpConnect = new Client();
        $response = $httpConnect->request($uri, [], 'DELETE', $this->headers());
        $this->httpCode = $httpConnect->getHttpCode();
        $response = is_string($response) ? json_decode($response) : $response;

        $this->setResults($response);

        if (Assist::getProperty($response, 'errors')) {
            $this->addError($response->errors);
            return $response->errors;
        }
        $response = !is_null(Assist::getProperty($response, $this->getPluralName())) ? $response->{$this->getPluralName()} : $response;
        return $response;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param mixed $product_id
     * @return Metafield
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVariantId()
    {
        return $this->variant_id;
    }

    /**
     * @param mixed $variant_id
     * @return Metafield
     */
    public function setVariantId($variant_id)
    {
        $this->variant_id = $variant_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     * @return Metafield
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param mixed $access_token
     * @return Metafield
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     * @return Metafield
     */
    public function setResults($results)
    {
        $this->results = $results;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     * @return Metafield
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param mixed $httpCode
     * @return Metafield
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
        return $this;
    }

}