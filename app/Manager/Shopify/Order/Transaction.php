<?php

namespace App\Manager\Shopify\Order;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Shopify\Shopify;

class Transaction extends Shopify
{
    const SINGULAR_NAME = 'transaction';
    const PLURAL_NAME   = 'transactions';

    protected $id;
    protected $order_id;
    protected $amount;
    protected $kind;
    protected $gateway;
    protected $status;
    protected $message;
    protected $created_at;
    protected $test;
    protected $authorization;
    protected $currency;
    protected $location_id;
    protected $user_id;
    protected $parent_id;
    protected $device_id;
    protected $receipt;
    protected $error_code;
    protected $source_name;
    protected $payment_details;
    protected $admin_graphql_api_id;

    public function fetch()
    {
        if( is_null( $this->getOrderId() ) ) return false;

        $uri         = $this->restAdminUri() . 'orders' . DIRECTORY_SEPARATOR . $this->getOrderId() . DIRECTORY_SEPARATOR . self::PLURAL_NAME . '.json';
        $httpConnect = new Client();
        $response    = $httpConnect->request( $uri ,[], 'GET', $this->headers() );

        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response  , self::PLURAL_NAME ) ) {
            $response = $response->{self::PLURAL_NAME};
        }
        else if( Assist::getProperty( $response  , 'errors' ) ) {
            $errors = $response->errors;
            $this->setErrors( $errors ) ;
        }
        $this->setResults( $response ) ;

        return $response ;
    }

    public function getPluralName()
    {
        return self::PLURAL_NAME;
    }

    public function getSingularName()
    {
        return self::SINGULAR_NAME;
    }

    /**
     * @return mixed
     */
    public function getPaymentDetails()
    {
        return $this->payment_details;
    }

    /**
     * @param mixed $payment_details
     * @return Transaction
     */
    public function setPaymentDetails($payment_details)
    {
        $this->payment_details = $payment_details;
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
     * @return Transaction
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
     * @return Transaction
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param mixed $kind
     * @return Transaction
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     * @return Transaction
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
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
     * @return Transaction
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Transaction
     */
    public function setMessage($message)
    {
        $this->message = $message;
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
     * @return Transaction
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param mixed $test
     * @return Transaction
     */
    public function setTest($test)
    {
        $this->test = $test;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @param mixed $authorization
     * @return Transaction
     */
    public function setAuthorization($authorization)
    {
        $this->authorization = $authorization;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return Transaction
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
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
     * @return Transaction
     */
    public function setLocationId($location_id)
    {
        $this->location_id = $location_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return Transaction
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param mixed $parent_id
     * @return Transaction
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * @param mixed $device_id
     * @return Transaction
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;
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
     * @return Transaction
     */
    public function setReceipt($receipt)
    {
        $this->receipt = $receipt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * @param mixed $error_code
     * @return Transaction
     */
    public function setErrorCode($error_code)
    {
        $this->error_code = $error_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSourceName()
    {
        return $this->source_name;
    }

    /**
     * @param mixed $source_name
     * @return Transaction
     */
    public function setSourceName($source_name)
    {
        $this->source_name = $source_name;
        return $this;
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
     * @return Transaction
     */
    public function setAdminGraphqlApiId($admin_graphql_api_id)
    {
        $this->admin_graphql_api_id = $admin_graphql_api_id;
        return $this;
    }


}