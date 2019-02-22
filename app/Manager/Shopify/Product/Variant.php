<?php

namespace App\Manager\Shopify\Product;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Basic\Status;
use App\Manager\Shopify\AbstractObject;
use \App\Manager\Shopify\Product\Variant\Metafield;
use App\Manager\Shopify\Product\Variant\PresentmentPrice;
use App\Manager\Shopify\Shopify;

class Variant extends Shopify
{
    protected $tax_code;
    protected $barcode;
    protected $compare_at_price;
    protected $created_at;
    protected $fulfillment_service;
    protected $grams;
    protected $weight;
    protected $weight_unit;
    protected $id;
    protected $inventory_item_id;
    protected $inventory_management;
    protected $inventory_policy;
    protected $inventory_quantity;
    protected $option1;
    protected $option2;
    protected $option3;
    protected $position;
    protected $price;
    protected $product_id;
    protected $requires_shipping;
    protected $sku;
    protected $taxable;
    protected $title;
    protected $updated_at;
    protected $image_id;
    protected $old_inventory_quantity;
    protected $admin_graph_ql_api_id;
    protected $metafields = [];
    protected $presentment_prices = [];

    public function getSingularName()
    {
        return 'variant';
    }

    public function getPluralName()
    {
        return 'variants';
    }

    public function toEloquent($shopOwnerId = null)
    {
        $eloquent = \App\Model\Variant::where('id', $this->getId())->first();
        if (! isset($eloquent)) {
            $eloquent = new \App\Model\Variant();
            $eloquent->id = $this->getId();
        }

        $eloquent->shop_owner_id = $shopOwnerId;
        $eloquent->title = $this->getTitle();
        $eloquent->sku = $this->getSku();
        $eloquent->inventory_item_id = $this->getInventoryItemId();
        $eloquent->meta = json_encode($this);
        $eloquent->status = Status::PENDING;
        $eloquent->product_id = $this->getProductId();

        return $eloquent;
    }

    public function load($variant)
    {
        parent::process($variant);
        return $this;
    }

    public function update($id)
    {
        $url = $this->restAdminUri() . $this->getPluralName() . DIRECTORY_SEPARATOR . $id . '.json';

        $client = new Client();
        $data = [ $this->getSingularName() => $this ];
        $result = $client->request( $url , $data , 'PUT' , $this->headers() ) ;

        $this->httpCode = $client->getHttpCode() ;
        $this->setResults( $result );
        return $this;
    }

    public function insert(){
        $uri      = 'https://' . $this->getShop() . '/admin/products/' . $this->getProductId() . '/variants.json';
        $httpConnect = new Client();
        $response = $httpConnect->request( $uri ,[ $this->getSingularName() => $this ], 'POST', $this->headers() );

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

    public function process( $variant )
    {

        if( ! is_object( $variant ) ) return null;

        $metafields = $this->getProperty( $variant , 'metafields' ) ;

        if( is_array( $metafields ) && ! empty( $metafields ) ){

            foreach ( $metafields as $metafield ){
                $variantMetafield = new Metafield();
                $this->addMetafield( $variantMetafield->process( $metafield ) ) ;
            }
        }

        $this->setAdminGraphQlApiId( $this->getProperty( $variant , 'admin_graph_ql_api_id' ) )
            ->setBarcode( $this->getProperty( $variant , 'barcode' ) )
            ->setCompareAtPrice( $this->getProperty( $variant , 'compare_at_price' ) )
            ->setCreatedAt($this->getProperty( $variant , 'created_at' ) )
            ->setFulfillmentService($this->getProperty( $variant , 'fulfillment_service' ) )
            ->setGrams($this->getProperty( $variant , 'grams' ) )
            ->setWeight($this->getProperty( $variant , 'weight' ) )
            ->setWeightUnit($this->getProperty( $variant , 'weight_unit' ) )
            ->setId($this->getProperty( $variant , 'id' ) )
            ->setInventoryItemId($this->getProperty( $variant , 'inventory_item_id' ) )
            ->setInventoryManagement($this->getProperty( $variant , 'inventory_management' ) )
            ->setInventoryPolicy($this->getProperty( $variant , 'inventory_policy' ) )
            ->setInventoryQuantity($this->getProperty( $variant , 'inventory_quantity' ) )
            ->setOption1($this->getProperty( $variant , 'option1' ) )
            ->setOption2($this->getProperty( $variant , 'option2' ) )
            ->setOption3($this->getProperty( $variant , 'option3' ) )
            ->setPosition($this->getProperty( $variant , 'position' ) )
            ->setPrice($this->getProperty( $variant , 'price' ) )
            ->setProductId($this->getProperty( $variant , 'product_id' ) )
            ->setRequiresShipping($this->getProperty( $variant , 'requires_shipping' ) )
            ->setSku($this->getProperty( $variant , 'sku' ) )
            ->setTaxable($this->getProperty( $variant , 'tax' ) )
            ->setTitle($this->getProperty( $variant , 'title' ) )
            ->setUpdatedAt($this->getProperty( $variant , 'updated_at' ) )
            ->setImageId($this->getProperty( $variant , 'image_id' ) )
            ->setOldInventoryQuantity($this->getProperty( $variant , 'old_inventory_quantity' ) );

        return $this;
    }

    /**
     * @return Metafield[]
     */
    public function getMetafields()
    {
        return $this->metafields;
    }

    /**
     * @param mixed $metafields
     * @return Variant
     */
    public function setMetafields($metafields)
    {
        $this->metafields = $metafields;
        return $this;
    }

    public function addMetafield( Metafield $metafield )
    {
        $metafields = $this->getMetafields();
        $metafields[] = $metafield;
        $this->setMetafields( $metafields );
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdminGraphQlApiId()
    {
        return $this->admin_graph_ql_api_id;
    }

    /**
     * @param mixed $admin_graph_ql_api_id
     * @return Variant
     */
    public function setAdminGraphQlApiId($admin_graph_ql_api_id)
    {
        $this->admin_graph_ql_api_id = $admin_graph_ql_api_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOldInventoryQuantity()
    {
        return $this->old_inventory_quantity;
    }

    /**
     * @param mixed $old_inventory_quantity
     * @return Variant
     */
    public function setOldInventoryQuantity($old_inventory_quantity)
    {
        $this->old_inventory_quantity = $old_inventory_quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageId()
    {
        return $this->image_id;
    }

    /**
     * @param mixed $image_id
     * @return Variant
     */
    public function setImageId($image_id)
    {
        $this->image_id = $image_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param mixed $barcode
     * @return Variant
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompareAtPrice()
    {
        return $this->compare_at_price;
    }

    /**
     * @param mixed $compare_at_price
     * @return Variant
     */
    public function setCompareAtPrice($compare_at_price)
    {
        $this->compare_at_price = $compare_at_price;
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
     * @return Variant
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFulfillmentService()
    {
        return $this->fulfillment_service;
    }

    /**
     * @param mixed $fulfillment_service
     * @return Variant
     */
    public function setFulfillmentService($fulfillment_service)
    {
        $this->fulfillment_service = $fulfillment_service;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrams()
    {
        return $this->grams;
    }

    /**
     * @param mixed $grams
     * @return Variant
     */
    public function setGrams($grams)
    {
        $this->grams = $grams;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     * @return Variant
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeightUnit()
    {
        return $this->weight_unit;
    }

    /**
     * @param mixed $weight_unit
     * @return Variant
     */
    public function setWeightUnit($weight_unit)
    {
        $this->weight_unit = $weight_unit;
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
     * @return Variant
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Variant
     */
    public function setInventoryItemId($inventory_item_id)
    {
        $this->inventory_item_id = $inventory_item_id;
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
     * @return Variant
     */
    public function setInventoryManagement($inventory_management)
    {
        $this->inventory_management = $inventory_management;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInventoryPolicy()
    {
        return $this->inventory_policy;
    }

    /**
     * @param mixed $inventory_policy
     * @return Variant
     */
    public function setInventoryPolicy($inventory_policy)
    {
        $this->inventory_policy = $inventory_policy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInventoryQuantity()
    {
        return $this->inventory_quantity;
    }

    /**
     * @param mixed $inventory_quantity
     * @return Variant
     */
    public function setInventoryQuantity($inventory_quantity)
    {
        $this->inventory_quantity = $inventory_quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOption1()
    {
        return $this->option1;
    }

    /**
     * @param mixed $option1
     * @return Variant
     */
    public function setOption1($option1)
    {
        $this->option1 = $option1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     * @return Variant
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return Variant
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
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
     * @return Variant
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequiresShipping()
    {
        return $this->requires_shipping;
    }

    /**
     * @param mixed $requires_shipping
     * @return Variant
     */
    public function setRequiresShipping($requires_shipping)
    {
        $this->requires_shipping = $requires_shipping;
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
     * @return Variant
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxable()
    {
        return $this->taxable;
    }

    /**
     * @param mixed $taxable
     * @return Variant
     */
    public function setTaxable($taxable)
    {
        $this->taxable = $taxable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Variant
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @return Variant
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOption2()
    {
        return $this->option2;
    }

    /**
     * @param mixed $option2
     * @return Variant
     */
    public function setOption2($option2)
    {
        $this->option2 = $option2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOption3()
    {
        return $this->option3;
    }

    /**
     * @param mixed $option3
     * @return Variant
     */
    public function setOption3($option3)
    {
        $this->option3 = $option3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxCode()
    {
        return $this->tax_code;
    }

    /**
     * @param mixed $tax_code
     * @return Variant
     */
    public function setTaxCode($tax_code)
    {
        $this->tax_code = $tax_code;
        return $this;
    }

    /**
     * @return array|Variant\PresentmentPrice[]
     */
    public function getPresentmentPrices()
    {
        return $this->presentment_prices;
    }

    /**
     * @param array|Variant\PresentmentPrice[] $presentment_prices
     * @return Variant
     */
    public function setPresentmentPrices($presentment_prices)
    {
        $this->presentment_prices = $presentment_prices;
        return $this;
    }

    /**
     * @param PresentmentPrice $presentmentPrice
     * @return $this
     */
    public function addPresentmentPrice( PresentmentPrice $presentmentPrice ){
        $presentmentPrices = $this->getPresentmentPrices();
        $presentmentPrices[] = $presentmentPrice;
        $this->setPresentmentPrices( $presentmentPrices ) ;

        return $this;
    }



}