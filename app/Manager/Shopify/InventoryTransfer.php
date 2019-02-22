<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 10/25/18
 * Time: 9:16 AM
 */

namespace App\Manager\Shopify;


use App\Manager\Basic\Client;
use App\Manager\Shopify\InventoryTransfer\LineItem;

class InventoryTransfer extends Shopify
{

    const SINGULAR_NAME = 'inventory_transfer';
    const PLURAL_NAME   = 'inventory_transfers';

    protected $id;
    protected $created_at;
    protected $updated_at;
    protected $archived_at;
    protected $expected_arrival;
    protected $placed_at;
    protected $status;
    protected $reference;
    protected $name;
    protected $tags;
    protected $line_items = [];

    public function getSingularName()
    {
        return self::SINGULAR_NAME;
    }

    public function getPluralName()
    {
        return self::PLURAL_NAME;
    }

    public function all($limit = 250, $page = 1, $fields = '', $status = 'any')
    {
        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) ) return false;
        $uri      = 'https://' . $this->getShop() . "/admin/transfers.json?status=$status&fulfillment_status=any&limit=$limit&page=$page&fields=$fields";
        return $this->request( $uri , 'GET' , [] );
    }

    public function get($id , $exists = false )
    {
        if( is_null( $this->getShop() ) || is_null( $this->getAccessToken() ) ) return false;
        $uri      = "https://" . $this->getShop() . "/admin/transfers/$id.json?";

        return $this->request( $uri , 'GET' , [] );
    }

    public function request( $uri , $type , array $data = [] )
    {

        $client         = new Client();
        $response       = $client->request( $uri , $data , $type , $this->headers() ) ;
        $this->httpCode = $client->getHttpCode() ;
        
        return $response; 
    }

    /**
     * @return mixed
     */
    public function getArchivedAt()
    {
        return $this->archived_at;
    }

    /**
     * @param mixed $archived_at
     */
    public function setArchivedAt($archived_at)
    {
        $this->archived_at = $archived_at;
    }

    /**
     * @return mixed
     */
    public function getExpectedArrival()
    {
        return $this->expected_arrival;
    }

    /**
     * @param mixed $expected_arrival
     */
    public function setExpectedArrival($expected_arrival)
    {
        $this->expected_arrival = $expected_arrival;
    }

    /**
     * @return mixed
     */
    public function getPlacedAt()
    {
        return $this->placed_at;
    }

    /**
     * @param mixed $placed_at
     */
    public function setPlacedAt($placed_at)
    {
        $this->placed_at = $placed_at;
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
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return LineItem[]
     */
    public function getLineItems()
    {
        return $this->line_items;
    }

    /**
     * @param LineItem[] $line_items
     */
    public function setLineItems($line_items)
    {
        $this->line_items = $line_items;
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
     */
    public function setId($id)
    {
        $this->id = $id;
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
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
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
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @param LineItem $lineItem
     * @return $this
     */
    public function addLineItem( LineItem $lineItem )
    {
        $lineItems = $this->getLineItems();
        $lineItems[] = $lineItem;
        $this->setLineItems( $lineItems );

        return $this;
    }
    
}