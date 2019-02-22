<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 10/24/18
 * Time: 12:36 PM
 */

namespace App\Manager\Shopify;

abstract class AbstractPrice extends AbstractObject
{

    const US_CURRENCY_CODE = 'USD';
    const EURO_CURRENCY_CODE = 'EUR';

    protected $currency_code;
    protected $amount;

    /**
     * @return mixed
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * @param mixed $currency_code
     * @return AbstractPrice
     */
    public function setCurrencyCode($currency_code)
    {
        $this->currency_code = $currency_code;
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
     * @return AbstractPrice
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }


}