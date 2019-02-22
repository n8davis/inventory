<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 9/13/18
 * Time: 6:18 PM
 */

namespace App\Manager\Shopify;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;

class InventoryItem extends Shopify
{
    const SINGULAR_NAME = 'inventory_item';
    const PLURAL_NAME   = 'inventory_items';

    protected $created_at;
    protected $updated_at;
    protected $id;
    protected $sku;
    protected $cost;
    protected $tracked;
    protected $ids = [];

    public function load($item)
    {
        parent::process($item);
        return $this;
    }

    /**
     * @param int $limit
     * @param int $page
     * @return array|InventoryItem[]
     */
    public function fetch( $limit = 250 , $page = 1 )
    {
        $uri = $this->restAdminUri() . $this->getPluralName() . '.json?' ;

        if( ! empty( $this->getIds() ) ) {
            $uri .= 'ids=' . implode( ',' , $this->getIds() );
        }

        $uri         .= '&limit=' . $limit . '&page=' . $page ;
        $httpConnect  = new Client();

        $response     = $httpConnect->request( $uri ,[], 'GET', $this->headers() );
        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;
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
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return InventoryItem
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
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
     * @return InventoryItem
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
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
     * @return InventoryItem
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     * @return InventoryItem
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTracked()
    {
        return $this->tracked;
    }

    /**
     * @param mixed $tracked
     * @return InventoryItem
     */
    public function setTracked($tracked)
    {
        $this->tracked = $tracked;
        return $this;
    }

    /**
     * @return array
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param array $ids
     * @return InventoryItem
     */
    public function setIds($ids)
    {
        $this->ids = $ids;
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function addId( $id )
    {
        $ids = $this->getId();
        $ids[] = $id;
        $this->setId( $ids ) ;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     * @return InventoryItem
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

}