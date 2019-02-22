<?php

namespace App\Manager\Shopify\Order;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;

class Fulfillment extends \App\Manager\Shopify\Shopify
{
    const SINGULAR_NAME = 'fulfillment' ;
    const PLURAL_NAME = 'fulfillments';

    protected $created_at;
    protected $id;
    protected $order_id;
    protected $status;
    protected $tracking_company;
    protected $tracking_number;
    protected $updated_at;
    protected $service;
    protected $shipment_status;
    protected $location_id;
    protected $email;
    protected $destination;
    protected $tracking_numbers;
    protected $tracking_url;
    protected $tracking_urls;
    protected $receipt;
    protected $name;
    protected $line_items;
    protected $admin_graphql_api_id;

    private $_type;

    public function getPluralName()
    {
        return self::PLURAL_NAME;
    }

    public function getSingularName()
    {
        return self::SINGULAR_NAME;
    }

    /**
     * @return array
     */
    public function headers()
    {
        return [
            "Content-Type: application/json",
            "X-Shopify-Access-Token: " . $this->getAccessToken()
        ];
    }

    protected function request( $uri )
    {

        if( is_null( $this->getId() ) ) $property = self::PLURAL_NAME;
        else $property = self::SINGULAR_NAME;

        $data = strtoupper( $this->_type ) === 'GET' ? [] : [ 'fulfillment' => json_decode( json_encode( $this ) , true ) ];

        $httpConnect    = new Client();
        $response       = $httpConnect->request( $uri , $data , $this->_type , $this->headers() ) ;
        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response,'errors')) {
            $this->addError( $response->errors );
            return $response;
        }

        $response =  !is_null( Assist::getProperty($response, $property)) ? $response->{$property} : $response;
        return $response ;
    }

    /**
     * @return bool|mixed
     */
    public function fetch()
    {

        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) || is_null( $this->getOrderId() ) ) return false;

        $this->_type = 'GET';
        $uri = $uri  = $this->restAdminUri() . 'orders' . DIRECTORY_SEPARATOR . $this->getOrderId() . DIRECTORY_SEPARATOR;

        if( is_null( $this->getId() ) ) $uri .= 'fulfillments.json';
        else $uri .= 'fulfillments' . DIRECTORY_SEPARATOR . $this->getId() . '.json';

        $uri .= '?status=any';

        return $this->request( $uri );

    }

    /**
     * Creates Fulfillment in Shopify API
     * @link https://help.shopify.com/en/api/reference/shipping_and_fulfillment/fulfillment#create
     * @return bool|mixed
     */
    public function insert()
    {
        if( is_null( $this->getOrderId() ) ) return false;

        $this->_type = 'POST';
        $uri         = $this->restAdminUri() . 'orders'  .
            DIRECTORY_SEPARATOR . $this->getOrderId() .
            DIRECTORY_SEPARATOR . 'fulfillments.json';

        return $this->request( $uri ) ;
    }

    /**
     * Updates Fulfillment in Shopify API
     * @link https://help.shopify.com/en/api/reference/shipping_and_fulfillment/fulfillment#update
     * @param $id
     * @return bool|mixed
     */
    public function update( $id )
    {
        if( is_null( $this->getOrderId() ) || is_null( $this->getId() ) ) return false;

        $this->_type = 'PUT';
        $uri         = $this->restAdminUri() . 'orders'  .
            DIRECTORY_SEPARATOR . $this->getOrderId() .
            DIRECTORY_SEPARATOR . 'fulfillments' .
            DIRECTORY_SEPARATOR . $this->getId() . '.json';

        return $this->request( $uri ) ;

    }

    /**
     * Marks Fulfillment as Complete
     * @link https://help.shopify.com/en/api/reference/shipping_and_fulfillment/fulfillment#complete
     * @return bool|mixed
     */
    public function complete()
    {
        if( is_null( $this->getOrderId() ) || is_null( $this->getId() ) ) return false;

        $this->_type = 'POST';
        $uri         = $this->restAdminUri() . 'orders'  .
            DIRECTORY_SEPARATOR . $this->getOrderId() .
            DIRECTORY_SEPARATOR . 'fulfillments' .
            DIRECTORY_SEPARATOR . $this->getId() .
            DIRECTORY_SEPARATOR . 'complete.json';

        return $this->request( $uri ) ;

    }

    /**
     * Marks Fulfillment as Cancelled
     * @link https://help.shopify.com/en/api/reference/shipping_and_fulfillment/fulfillment#cancel
     * @return bool|mixed
     */
    public function cancel()
    {
        if( is_null( $this->getOrderId() ) || is_null( $this->getId() ) ) return false;

        $this->_type = 'POST';
        $uri         = $this->restAdminUri() . 'orders'  .
            DIRECTORY_SEPARATOR . $this->getOrderId() .
            DIRECTORY_SEPARATOR . 'fulfillments' .
            DIRECTORY_SEPARATOR . $this->getId()  .
            DIRECTORY_SEPARATOR . 'cancel.json';
Assist::consoleLog( $uri ) ; 
        return $this->request( $uri ) ;

    }

    /**
     * Marks Fulfillment as Open
     * @link https://help.shopify.com/en/api/reference/shipping_and_fulfillment/fulfillment#open
     * @return bool|mixed
     */
    public function open()
    {
        if( is_null( $this->getOrderId() ) || is_null( $this->getId() ) ) return false;

        $this->_type = 'POST';
        $uri         = $this->restAdminUri() . 'orders'  .
            DIRECTORY_SEPARATOR . $this->getOrderId() .
            DIRECTORY_SEPARATOR . 'fulfillments' .
            DIRECTORY_SEPARATOR . $this->getId()  .
            DIRECTORY_SEPARATOR . 'open.json';

        return $this->request( $uri ) ;

    }

    /**
     * @return mixed
     */
    public function getAdminGraphqlApiId()
    {
        return $this->admin_graphql_api_id;
    }

    /**
     * @param mixed $admin_graphql_api_id
     * @return Fulfillment
     */
    public function setAdminGraphqlApiId($admin_graphql_api_id)
    {
        $this->admin_graphql_api_id = $admin_graphql_api_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShipmentStatus()
    {
        return $this->shipment_status;
    }

    /**
     * @param mixed $shipment_status
     * @return Fulfillment
     */
    public function setShipmentStatus($shipment_status)
    {
        $this->shipment_status = $shipment_status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocationId()
    {
        return $this->location_id;
    }

    /**
     * @param mixed $location_id
     * @return Fulfillment
     */
    public function setLocationId($location_id)
    {
        $this->location_id = $location_id;
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
     * @return Fulfillment
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param mixed $destination
     * @return Fulfillment
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrackingNumbers()
    {
        return $this->tracking_numbers;
    }

    /**
     * @param mixed $tracking_numbers
     * @return Fulfillment
     */
    public function setTrackingNumbers($tracking_numbers)
    {
        $this->tracking_numbers = $tracking_numbers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrackingUrl()
    {
        return $this->tracking_url;
    }

    /**
     * @param mixed $tracking_url
     * @return Fulfillment
     */
    public function setTrackingUrl($tracking_url)
    {
        $this->tracking_url = $tracking_url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrackingUrls()
    {
        return $this->tracking_urls;
    }

    /**
     * @param mixed $tracking_urls
     * @return Fulfillment
     */
    public function setTrackingUrls($tracking_urls)
    {
        $this->tracking_urls = $tracking_urls;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * @param mixed $receipt
     * @return Fulfillment
     */
    public function setReceipt($receipt)
    {
        $this->receipt = $receipt;
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
     * @return Fulfillment
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLineItems()
    {
        return $this->line_items;
    }

    /**
     * @param mixed $line_items
     * @return Fulfillment
     */
    public function setLineItems($line_items)
    {
        $this->line_items = $line_items;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     * @return Fulfillment
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return Fulfillment
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
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
     * @return Fulfillment
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param mixed $order_id
     * @return Fulfillment
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Fulfillment
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrackingCompany()
    {
        return $this->tracking_company;
    }

    /**
     * @param mixed $tracking_company
     * @return Fulfillment
     */
    public function setTrackingCompany($tracking_company)
    {
        $this->tracking_company = $tracking_company;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrackingNumber()
    {
        return $this->tracking_number;
    }

    /**
     * @param mixed $tracking_number
     * @return Fulfillment
     */
    public function setTrackingNumber($tracking_number)
    {
        $this->tracking_number = $tracking_number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     * @return Fulfillment
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }



}