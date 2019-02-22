<?php

namespace App\Manager\Shopify;

use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Basic\Status;


class InventoryLevel extends Shopify
{
    const SINGULAR_NAME = 'inventory_level';
    const PLURAL_NAME   = 'inventory_levels';

    protected $available;
    protected $inventory_item_id;
    protected $location_id;
    protected $updated_at;
    protected $relocate_if_necessary;
    protected $disconnect_if_necessary;
    protected $admin_graphql_api_id;

    protected $_location_ids = [];
    protected $_inventory_item_ids = [];

    private $_type;

    public function load($item)
    {
        parent::process($item);
        return $this;
    }

    /**
     * Makes request to Shopify API
     *
     * @param $uri
     * @return mixed
     */
    public function request( $uri )
    {

        $httpConnect    = new Client();
        $data           = strtoupper( $this->_type ) === 'GET' ? [] : json_decode( json_encode( $this ), true ) ;

        $response       = $httpConnect->request( $uri , $data , $this->_type, $this->headers() );

        $this->setResults( $response ) ;

        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;
        $response       = ! is_null( Assist::getProperty($response, $this->getPluralName())) ? $response->{$this->getPluralName()} : $response;


        if( is_object( $response ) && property_exists( $response , 'errors' ) ){
            $this->addError(  $response->errors  ) ;
        }
        return $response ;

    }

    /**
     * Sets the inventory level for an inventory item at a location. If the specified location is not connected, it will be automatically connected first. When connecting inventory items to locations, it's important to understand the rules around fulfillment service locations.
     *
     * @link https://help.shopify.com/en/api/reference/inventory/inventorylevel#set
     *
     * @return mixed
     */
    public function set()
    {
        $this->_type = "POST";
        return $this->request( $this->restAdminUri() . $this->getPluralName() . DIRECTORY_SEPARATOR .  'set.json' );
    }

    /**
     * Retrieves a list of inventory levels. You must include inventory_item_ids, location_ids, or both as filter parameters.
     *
     * @link https://help.shopify.com/en/api/reference/inventory/inventorylevel#index
     *
     * @param int $limit
     * @param int $page
     * @return mixed
     */
    public function fetch( $limit = 250 , $page = 1 )
    {
        $this->_type = "GET";

        $uri = $this->restAdminUri() . $this->getPluralName() . '.json?' ;
        if( ! empty( $this->getLocationIds() ) ) {
            $uri .= 'location_ids=' . implode( ',' , $this->getLocationIds() ) . '&';
        }
        if( ! empty( $this->getInventoryItemIds() ) ) {
            $uri .= 'inventory_item_ids=' . implode( ',' , $this->getInventoryItemIds() ) . '&';
        }

        $uri .= 'limit=' . $limit . '&page=' . $page ;

        $response = $this->request( $uri );

        $response = is_string( $response ) ? json_decode( $response ) : $response ;
        $response =  !is_null( Assist::getProperty($response, $this->getPluralName()))
            ? $response->{$this->getPluralName()}
            : $response;
        $items = [];
        if (!empty($response)){
            foreach($response as $key => $value){
                $items[] = $this->load($value);
            }
        }
        return $items;
    }

    /**
     * Connects an inventory item to a location by creating an inventory level at that location. When connecting inventory items to locations, it's important to understand the rules around fulfillment service locations.
     *
     * @link https://help.shopify.com/en/api/reference/inventory/inventorylevel#connect
     *
     * @return mixed
     */
    public function connect()
    {
        $this->_type = "POST";
        return $this->request( $this->restAdminUri() . $this->getPluralName() . DIRECTORY_SEPARATOR .  'connect.json' );
    }

    /**
     * @param $shopOwnerId
     * @return \App\Model\InventoryLevel
     */
    public function toEloquent($shopOwnerId)
    {
        $eloquent = \App\Model\InventoryLevel
            ::where('inventory_item_id', $this->getInventoryItemId())
            ->first();

        if (!isset($eloquent)) {
            $eloquent = new \App\Model\InventoryLevel();
            $eloquent->inventory_item_id = $this->getInventoryItemId();
        }

        $eloquent->available = $this->getAvailable();
        $eloquent->location_id = $this->getLocationId();
        $eloquent->meta = json_encode($this);
        $eloquent->shop_owner_id = $shopOwnerId;
        $eloquent->status = Status::PENDING;

        return $eloquent;
    }

    /**
     * @return string
     */
    public function getSingularName()
    {
        return self::SINGULAR_NAME;
    }

    /**
     * @return string
     */
    public function getPluralName()
    {
        return self::PLURAL_NAME;
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
     * @return InventoryLevel
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
     * @return InventoryLevel
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function addLocationId( $id ){
        $ids = $this->getLocationIds() ;
        if( ! in_array( $id , $ids ) ) $ids[] = $id;
        $this->setLocationIds( $ids ) ;
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function addInventoryItemId( $id ){
        $ids = $this->getInventoryItemIds() ;
        if( ! in_array( $id , $ids ) ) $ids[] = $id;
        $this->setInventoryItemIds( $ids ) ;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @param mixed $available
     * @return InventoryLevel
     */
    public function setAvailable($available)
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInventoryItemId()
    {
        return $this->inventory_item_id;
    }

    /**
     * @param mixed $inventory_item_id
     * @return InventoryLevel
     */
    public function setInventoryItemId($inventory_item_id)
    {
        $this->inventory_item_id = $inventory_item_id;
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
     * @return InventoryLevel
     */
    public function setLocationId($location_id)
    {
        $this->location_id = $location_id;
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
     * @return InventoryLevel
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return array
     */
    public function getInventoryItemIds()
    {
        return $this->_inventory_item_ids;
    }

    /**
     * @param array $inventory_item_ids
     * @return InventoryLevel
     */
    public function setInventoryItemIds($inventory_item_ids)
    {
        $this->_inventory_item_ids = $inventory_item_ids;
        return $this;
    }

    /**
     * @return array
     */
    public function getLocationIds()
    {
        return $this->_location_ids;
    }

    /**
     * @param array $location_ids
     * @return InventoryLevel
     */
    public function setLocationIds(array $location_ids)
    {
        $this->_location_ids = $location_ids;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRelocateIfNecessary()
    {
        return $this->relocate_if_necessary;
    }

    /**
     * @param bool $relocate_if_necessary
     * @return InventoryLevel
     */
    public function setRelocateIfNecessary( $relocate_if_necessary)
    {
        $this->relocate_if_necessary = $relocate_if_necessary;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisconnectIfNecessary()
    {
        return $this->disconnect_if_necessary;
    }

    /**
     * @param mixed $disconnect_if_necessary
     * @return InventoryLevel
     */
    public function setDisconnectIfNecessary($disconnect_if_necessary)
    {
        $this->disconnect_if_necessary = $disconnect_if_necessary;
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
     * @return InventoryLevel
     */
    public function setAdminGraphqlApiId($admin_graphql_api_id)
    {
        $this->admin_graphql_api_id = $admin_graphql_api_id;
        return $this;
    }



}