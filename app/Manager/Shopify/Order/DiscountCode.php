<?php

namespace App\Manager\Shopify\Order;


use App\Manager\Shopify\AbstractObject;

class DiscountCode extends AbstractObject
{
    protected $code;
    protected $amount;
    protected $type;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return DiscountCode
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return DiscountCode
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return DiscountCode
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }



}