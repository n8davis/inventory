<?php

namespace App\Manager\Shopify;

use App\Manager\Basic\Client;

class FulfillmentService extends Shopify
{

    CONST NAME_SINGULAR = 'fulfillment_service';
    CONST NAME_PLURAL   = 'fulfillment_services';

    /**
     * Specifies the format of the API output. Valid values are json and xml.
     */
    CONST JSON_FORMAT = 'json';

    /**
     * Specifies the format of the API output. Valid values are json and xml.
     */
    CONST XML_FORMAT = 'xml';

    protected $callback_url ;
    protected $format;
    protected $handle;
    protected $inventory_management;
    protected $name;
    protected $provider_id;
    protected $requires_shipping_method;
    protected $tracking_support;

    public function getSingularName(){
        return self::NAME_SINGULAR;
    }

    public function getPluralName(){
        return self::NAME_PLURAL;
    }

    public function load($value)
    {
        parent::process($value);
        return $this;
    }

    public function fetch( $scope = null ){
        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) ) return false;

        $uri   = 'https://' . $this->getShop() . '/admin/' . $this->getPluralName() . '.json';

        if ( ! is_null( $scope ) ) $uri .= '?scope=' . $scope;

        $client         = new Client();
        $response       = $client->request( $uri ,[], 'GET', $this->headers() );
        $this->httpCode = $client->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;
        return json_decode( $response ) ;

    }


    /**
     * @return mixed
     */
    public function getCallbackUrl()
    {
        return $this->callback_url;
    }

    /**
     * @param mixed $callback_url
     * @return FulfillmentService
     */
    public function setCallbackUrl($callback_url)
    {
        $this->callback_url = $callback_url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     * @return FulfillmentService
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param mixed $handle
     * @return FulfillmentService
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInventoryManagement()
    {
        return $this->inventory_management;
    }

    /**
     * @param mixed $inventory_management
     * @return FulfillmentService
     */
    public function setInventoryManagement($inventory_management)
    {
        $this->inventory_management = $inventory_management;
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
     * @return FulfillmentService
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProviderId()
    {
        return $this->provider_id;
    }

    /**
     * @param mixed $provider_id
     * @return FulfillmentService
     */
    public function setProviderId($provider_id)
    {
        $this->provider_id = $provider_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequiresShippingMethod()
    {
        return $this->requires_shipping_method;
    }

    /**
     * @param mixed $requires_shipping_method
     * @return FulfillmentService
     */
    public function setRequiresShippingMethod($requires_shipping_method)
    {
        $this->requires_shipping_method = $requires_shipping_method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrackingSupport()
    {
        return $this->tracking_support;
    }

    /**
     * @param mixed $tracking_support
     * @return FulfillmentService
     */
    public function setTrackingSupport($tracking_support)
    {
        $this->tracking_support = $tracking_support;
        return $this;
    }



}