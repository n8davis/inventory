<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 10/25/18
 * Time: 9:20 AM
 */

namespace App\Manager\Shopify\InventoryTransfer;


use App\Manager\Shopify\AbstractObject;

class LineItem extends AbstractObject
{
    protected $id;
    protected $product_variant_id;
    protected $quantity;
    protected $created_at;
    protected $updated_at;
    protected $product_id;
    protected $cancelled_quantity;
    protected $accepted_quantity;
    protected $rejected_quantity;
    protected $remaining_quantity;

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
    public function getProductVariantId()
    {
        return $this->product_variant_id;
    }

    /**
     * @param mixed $product_variant_id
     * @return LineItem
     */
    public function setProductVariantId($product_variant_id)
    {
        $this->product_variant_id = $product_variant_id;
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
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return LineItem
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
     * @return LineItem
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
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
    public function getCancelledQuantity()
    {
        return $this->cancelled_quantity;
    }

    /**
     * @param mixed $cancelled_quantity
     * @return LineItem
     */
    public function setCancelledQuantity($cancelled_quantity)
    {
        $this->cancelled_quantity = $cancelled_quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcceptedQuantity()
    {
        return $this->accepted_quantity;
    }

    /**
     * @param mixed $accepted_quantity
     * @return LineItem
     */
    public function setAcceptedQuantity($accepted_quantity)
    {
        $this->accepted_quantity = $accepted_quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRejectedQuantity()
    {
        return $this->rejected_quantity;
    }

    /**
     * @param mixed $rejected_quantity
     * @return LineItem
     */
    public function setRejectedQuantity($rejected_quantity)
    {
        $this->rejected_quantity = $rejected_quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRemainingQuantity()
    {
        return $this->remaining_quantity;
    }

    /**
     * @param mixed $remaining_quantity
     * @return LineItem
     */
    public function setRemainingQuantity($remaining_quantity)
    {
        $this->remaining_quantity = $remaining_quantity;
        return $this;
    }


}