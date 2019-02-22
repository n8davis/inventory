<?php

namespace App\Manager\Shopify\Order;


use App\Manager\Shopify\AbstractObject;

class ShippingLine extends AbstractObject
{
    protected $code;
    protected $price;
    protected $source;
    protected $title;
    protected $tax_lines;
    protected $carrier_identifier;
    protected $requested_fulfillment_service_id;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return ShippingLine
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return ShippingLine
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     * @return ShippingLine
     */
    public function setSource($source)
    {
        $this->source = $source;
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
     * @return ShippingLine
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @return ShippingLine
     */
    public function setTaxLines($tax_lines)
    {
        $this->tax_lines = $tax_lines;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarrierIdentifier()
    {
        return $this->carrier_identifier;
    }

    /**
     * @param mixed $carrier_identifier
     * @return ShippingLine
     */
    public function setCarrierIdentifier($carrier_identifier)
    {
        $this->carrier_identifier = $carrier_identifier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestedFulfillmentServiceId()
    {
        return $this->requested_fulfillment_service_id;
    }

    /**
     * @param mixed $requested_fulfillment_service_id
     * @return ShippingLine
     */
    public function setRequestedFulfillmentServiceId($requested_fulfillment_service_id)
    {
        $this->requested_fulfillment_service_id = $requested_fulfillment_service_id;
        return $this;
    }



}