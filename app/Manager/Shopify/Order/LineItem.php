<?php

namespace App\Manager\Shopify\Order;


use App\Manager\Shopify\AbstractObject;
use App\Manager\Shopify\Order\LineItem\DiscountAllocation;
use App\Manager\Shopify\Order\LineItem\PriceSet;
use App\Manager\Shopify\Order\LineItem\TotalDiscountSet;

class LineItem extends AbstractObject
{

    protected $fulfillable_quantity;
    protected $fulfillment_service;
    protected $fulfillment_status;
    protected $grams;
    protected $id;
    protected $price;
    protected $product_id;
    protected $quantity;
    protected $requires_shipping;
    protected $sku;
    protected $title;
    protected $variant_id;
    protected $variant_title;
    protected $vendor;
    protected $name;
    protected $gift_card;
    protected $properties;
    protected $taxable;
    protected $tax_lines;
    protected $total_discount;
    protected $discount_allocations;
    protected $variant_inventory_management;
    protected $product_exists;
    protected $admin_graphql_api_id;
    protected $price_set;
    protected $total_discount_set;

    /**
     * @return mixed
     */
    public function getAdminGraphqlApiId()
    {
        return $this->admin_graphql_api_id;
    }

    /**
     * @param mixed $admin_graphql_api_id
     * @return LineItem
     */
    public function setAdminGraphqlApiId($admin_graphql_api_id)
    {
        $this->admin_graphql_api_id = $admin_graphql_api_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductExists()
    {
        return $this->product_exists;
    }

    /**
     * @param mixed $product_exists
     * @return LineItem
     */
    public function setProductExists($product_exists)
    {
        $this->product_exists = $product_exists;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVariantInventoryManagement()
    {
        return $this->variant_inventory_management;
    }

    /**
     * @param mixed $variant_inventory_management
     * @return LineItem
     */
    public function setVariantInventoryManagement($variant_inventory_management)
    {
        $this->variant_inventory_management = $variant_inventory_management;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFulfillableQuantity()
    {
        return $this->fulfillable_quantity;
    }

    /**
     * @param mixed $fulfillable_quantity
     * @return LineItem
     */
    public function setFulfillableQuantity($fulfillable_quantity)
    {
        $this->fulfillable_quantity = $fulfillable_quantity;
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
     * @return LineItem
     */
    public function setFulfillmentService($fulfillment_service)
    {
        $this->fulfillment_service = $fulfillment_service;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFulfillmentStatus()
    {
        return $this->fulfillment_status;
    }

    /**
     * @param mixed $fulfillment_status
     * @return LineItem
     */
    public function setFulfillmentStatus($fulfillment_status)
    {
        $this->fulfillment_status = $fulfillment_status;
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
     * @return LineItem
     */
    public function setGrams($grams)
    {
        $this->grams = $grams;
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
     * @return LineItem
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return LineItem
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
     * @return LineItem
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return LineItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
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
     * @return LineItem
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
     * @return LineItem
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
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
     * @return LineItem
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVariantId()
    {
        return $this->variant_id;
    }

    /**
     * @param mixed $variant_id
     * @return LineItem
     */
    public function setVariantId($variant_id)
    {
        $this->variant_id = $variant_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVariantTitle()
    {
        return $this->variant_title;
    }

    /**
     * @param mixed $variant_title
     * @return LineItem
     */
    public function setVariantTitle($variant_title)
    {
        $this->variant_title = $variant_title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param mixed $vendor
     * @return LineItem
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
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
     * @return LineItem
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGiftCard()
    {
        return $this->gift_card;
    }

    /**
     * @param mixed $gift_card
     * @return LineItem
     */
    public function setGiftCard($gift_card)
    {
        $this->gift_card = $gift_card;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param mixed $properties
     * @return LineItem
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
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
     * @return LineItem
     */
    public function setTaxable($taxable)
    {
        $this->taxable = $taxable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxLines()
    {
        return $this->tax_lines;
    }

    /**
     * @param mixed $tax_lines
     * @return LineItem
     */
    public function setTaxLines($tax_lines)
    {
        $this->tax_lines = $tax_lines;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalDiscount()
    {
        return $this->total_discount;
    }

    /**
     * @param mixed $total_discount
     * @return LineItem
     */
    public function setTotalDiscount($total_discount)
    {
        $this->total_discount = $total_discount;
        return $this;
    }

    /**
     * @return DiscountAllocation[]
     */
    public function getDiscountAllocations()
    {
        return $this->discount_allocations;
    }

    /**
     * @param DiscountAllocation[] $discount_allocations
     * @return LineItem
     */
    public function setDiscountAllocations($discount_allocations)
    {
        $this->discount_allocations = $discount_allocations;
        return $this;
    }

    /**
     * @param DiscountAllocation $discountAllocation
     * @return $this
     */
    public function addDiscountAllocation( $discountAllocation ){
        $allocations = $this->getDiscountAllocations();
        $allocations[] = $discountAllocation;
        $this->setDiscountAllocations( $allocations );
        return $this;
    }

    /**
     * @return PriceSet
     */
    public function getPriceSet()
    {
        return $this->price_set;
    }

    /**
     * @param PriceSet $price_set
     * @return LineItem
     */
    public function setPriceSet($price_set)
    {
        $this->price_set = $price_set;
        return $this;
    }

    /**
     * @return TotalDiscountSet
     */
    public function getTotalDiscountSet()
    {
        return $this->total_discount_set;
    }

    /**
     * @param TotalDiscountSet $total_discount_set
     * @return LineItem
     */
    public function setTotalDiscountSet( $total_discount_set)
    {
        $this->total_discount_set = $total_discount_set;
        return $this;
    }


}