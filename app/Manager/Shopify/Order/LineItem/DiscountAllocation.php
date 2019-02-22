<?php

namespace App\Manager\Shopify\Order\LineItem;


class DiscountAllocation
{

    protected $discount_application_index;
    protected $amount;

    /**
     * @return mixed
     */
    public function getDiscountApplicationIndex()
    {
        return $this->discount_application_index;
    }

    /**
     * @param mixed $discount_application_index
     * @return DiscountAllocation
     */
    public function setDiscountApplicationIndex($discount_application_index)
    {
        $this->discount_application_index = $discount_application_index;
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
     * @return DiscountAllocation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

}