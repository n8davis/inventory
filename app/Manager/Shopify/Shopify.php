<?php

namespace App\Manager\Shopify;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;

abstract class Shopify extends AbstractObject
{
    abstract function getSingularName();
    abstract function getPluralName();
    abstract function load($value);

    protected $shop;
    protected $access_token;
    protected $results;
    protected $errors;
    public $httpCode;


    public function graphAdminUri()
    {
        return 'https://' . $this->getShop() . '/admin/api/graphql.json';
    }

    public function restAdminUri()
    {
        return 'https://' . $this->getShop() . '/admin/' ;
    }

    public function headers()
    {
        return [
            "Accept: application/json",
            "Content-Type: application/json",
            "X-Shopify-Access-Token: " . $this->getAccessToken()
        ];
    }

    public function save( $id = NULL ){

        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) ) return false;

        if( $this->exists( $id ) > 0  && ! is_null( $id ) ) return $this->update( $id );

        else return $this->insert();


    }

    public function exists( $id = NULL ) {
        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) ) return false;
        if( is_null( $id ) ) :
            return false;
        else:
            $uri = 'https://' . $this->getShop() . '/admin/' . $this->getPluralName()  .'/' . $id . '.json';
        endif;
        $entity_id   = null;
        $httpConnect = new Client();

        $response       = $httpConnect->request( $uri ,[], 'GET', $this->headers() );
        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response  , $this->getSingularName() ) ) {
            if (Assist::getProperty( $response->{ $this->getSingularName() } , 'id' ) ) {
                $entity_id = $response->{$this->getSingularName()}->id;
            }
        }
        return $entity_id ;
    }

    public function count( $fields = '' , $status = 'any' ) {
        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) ) return false;
        $count       = null;
        $uri         = 'https://' . $this->getShop() . '/admin/' . $this->getPluralName()  .'/count.json?status=' . $status . '&fields=' . $fields ;
        $httpConnect = new Client();
        $response    = $httpConnect->request( $uri ,[], 'GET', $this->headers() );

        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response  , 'count') ) {
            $count = $response->count;
        }
        return $count ;
    }

    /**
     * @return bool|integer
     */
    public function insert(){
        $uri      = 'https://' . $this->getShop() . '/admin/' . $this->getPluralName() . '.json';
        $httpConnect = new Client();
        $response = $httpConnect->request( $uri ,[$this->getSingularName() => Assist::convertToArray($this) ], 'POST', $this->headers() );

        $this->setResults( $response ) ;

        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        if( Assist::getProperty( $response , 'errors' ) || ( $this->httpCode <= 599 && $this->httpCode >= 400 ) ){
            $this->addError( $response ) ;
            return $response ;
        }
        else{
            if(Assist::getProperty( $response , $this->getSingularName()  ) ){
                if( Assist::getProperty( $response->{ $this->getSingularName() }  , 'id' ) ){
                    return $response;
                }
            }
            return $response;
        }
    }

    public function update( $id  ){
        $uri            = 'https://' . $this->getShop() . '/admin/'. $this->getPluralName()  . '/' . $id . '.json';
        $httpConnect    = new Client();

        $response       = $httpConnect->request( $uri , [ $this->getSingularName() => $this ] , 'PUT', $this->headers() );

        $this->httpCode = $httpConnect->getHttpCode() ;
        $this->setResults( $response ) ;

        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $httpCode = $httpConnect->http_code;

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

    public function get( $id , $exists = false ){
        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) ) return false;
        if( is_null( $id ) ) return false;

        $uri = $this->restAdminUri() . $this->getPluralName() . DIRECTORY_SEPARATOR . $id . '.json?fulfillment_status=any&status=any';
        $entity_id   = null;
        $httpConnect = new Client();
        $results     = [];
        $response    = $httpConnect->request( $uri ,[], 'GET' , $this->headers() ) ;
        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response  , $this->getSingularName() ) ) {
            if (Assist::getProperty($response->{$this->getSingularName()}, 'id')) {
                $entity_id = $response->{$this->getSingularName()}->id;
            }
            $results = $response->{ $this->getSingularName() };
        }
        else if( Assist::getProperty( $response  , $this->getPluralName() ) ) {
            $results = $response->{ $this->getPluralName() };
        }
        return $exists === true ? $entity_id : $results ;
    }


    public function all($limit = 250 , $page = 1 , $fields = '' , $status = 'any' ){

        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) ) {
            return false;
        }

        $uri      = 'https://' . $this->getShop() . '/admin/' . $this->getPluralName()
            . '.json?status=' . $status . '&fulfillment_status=any&limit='
            . $limit . '&page=' . $page . '&fields=' . $fields ;

        $httpConnect    = new Client();
        $response       = $httpConnect->request( $uri ,[], 'GET' , $this->headers() ) ;
        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;
        $this->setResults( $response ) ;

        if( Assist::getProperty( $response,'errors')) {
            $this->addError( $response->errors );
            return $response;
        }

        $response =  !is_null( Assist::getProperty($response, $this->getPluralName()))
            ? $response->{$this->getPluralName()}
            : $response;
        return $response;
    }


    public function delete( $id ){
        if (is_null($this->getShop()) || is_null($this->getAccessToken())) return false;
        $uri = 'https://' . $this->getShop() . '/admin/' . $this->getPluralName() . DIRECTORY_SEPARATOR . $id . '.json';

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

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     * @return Shopify
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
     * @return Shopify
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
     * @return Shopify
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
     * @return Shopify
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param $error
     * @return $this
     */
    public function addError( $error )
    {
        $errors = $this->getErrors();
        $errors[] = $error;
        $this->setErrors( $errors );
        return $this;
    }


}