<?php

namespace App\Manager\Shopify;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Shopify\Shop\PrimaryDomain;

class Shop extends Shopify
{

    const SINGULAR_NAME = 'shop';
    const PLURAL_NAME   = 'shop';

    protected $id;
    protected $name;
    protected $email;
    protected $ianaTimezone;
    protected $primaryDomain;


    public function getSingularName()
    {
        return self::SINGULAR_NAME;
    }

    public function getPluralName()
    {
        return self::PLURAL_NAME;
    }

    public function load($shop)
    {
        if (is_object($shop)) {
            if (property_exists($shop,'shop')) {
                $shop = $shop->shop;
            }
        }
        $this->process($shop);
        return $this;
    }

    public function fetch()
    {
        $uri = 'https://' . $this->getShop() . '/admin/shop.json';

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
        $this->process($response);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Shop
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIanaTimezone()
    {
        return $this->ianaTimezone;
    }

    /**
     * @param mixed $ianaTimezone
     * @return Shop
     */
    public function setIanaTimezone($ianaTimezone)
    {
        $this->ianaTimezone = $ianaTimezone;
        return $this;
    }

    /**
     * @return PrimaryDomain
     */
    public function getPrimaryDomain()
    {
        return $this->primaryDomain;
    }

    /**
     * @param mixed $primaryDomain
     * @return Shop
     */
    public function setPrimaryDomain($primaryDomain)
    {
        $this->primaryDomain = $primaryDomain;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Shop
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Shop
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


}