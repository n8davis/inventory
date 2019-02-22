<?php

namespace App\Manager\Shopify\Order;

use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;


class Refund extends \App\Manager\Shopify\Shopify
{
    const SINGULAR_NAME = 'refund' ;
    const PLURAL_NAME = 'refunds';


    protected $id;
    protected $order_id;
    protected $created_at;
    protected $note;
    protected $user_id;
    protected $processed_at;
    protected $restock;
    protected $refund_line_items;
    protected $transactions;
    protected $order_adjustments;
    protected $admin_graphql_api_id;

    public function getPluralName()
    {
        return self::PLURAL_NAME;
    }

    public function getSingularName()
    {
        return self::SINGULAR_NAME;
    }

    public function fetch()
    {

        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) || is_null( $this->getOrderId() ) ) return false;

        $uri = $uri = $this->restAdminUri() . 'orders' . DIRECTORY_SEPARATOR . $this->getOrderId() . DIRECTORY_SEPARATOR;
        $property = null;
        if( is_null( $this->getId() ) ) {
            $property = self::PLURAL_NAME;
            $uri .= 'refunds.json';
        }
        else {
            $property = self::SINGULAR_NAME;
            $uri .= 'refunds' . $this->getId() . '.json';
        }

        $httpConnect = new Client();
        $response    = $httpConnect->request( $uri ,[], 'GET' , $this->headers() ) ;
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
     * @return mixed
     */
    public function getAdminGraphqlApiId()
    {
        return $this->admin_graphql_api_id;
    }

    /**
     * @param mixed $admin_graphql_api_id
     * @return Refund
     */
    public function setAdminGraphqlApiId($admin_graphql_api_id)
    {
        $this->admin_graphql_api_id = $admin_graphql_api_id;
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
     * @return Refund
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
     * @return Refund
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
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
     * @return Refund
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     * @return Refund
     */
    public function setNote($note)
    {
        $this->note = $note;
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
     * @return Refund
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessedAt()
    {
        return $this->processed_at;
    }

    /**
     * @param mixed $processed_at
     * @return Refund
     */
    public function setProcessedAt($processed_at)
    {
        $this->processed_at = $processed_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRestock()
    {
        return $this->restock;
    }

    /**
     * @param mixed $restock
     * @return Refund
     */
    public function setRestock($restock)
    {
        $this->restock = $restock;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRefundLineItems()
    {
        return $this->refund_line_items;
    }

    /**
     * @param mixed $refund_line_items
     * @return Refund
     */
    public function setRefundLineItems($refund_line_items)
    {
        $this->refund_line_items = $refund_line_items;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param mixed $transactions
     * @return Refund
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderAdjustments()
    {
        return $this->order_adjustments;
    }

    /**
     * @param mixed $order_adjustments
     * @return Refund
     */
    public function setOrderAdjustments($order_adjustments)
    {
        $this->order_adjustments = $order_adjustments;
        return $this;
    }


}