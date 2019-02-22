<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 10/24/18
 * Time: 12:34 PM
 */

namespace App\Manager\Shopify\Product\Variant;

use App\Manager\Shopify\AbstractObject;
use App\Manager\Shopify\Product\Variant\PresentmentPrice\CompareAtPrice;
use App\Manager\Shopify\Product\Variant\PresentmentPrice\Price;

class PresentmentPrice extends AbstractObject
{

    protected $price;
    protected $compare_at_price;

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param Price $price
     * @return PresentmentPrice
     */
    public function setPrice( Price $price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return CompareAtPrice
     */
    public function getCompareAtPrice()
    {
        return $this->compare_at_price;
    }

    /**
     * @param CompareAtPrice $compare_at_price
     * @return PresentmentPrice
     */
    public function setCompareAtPrice( CompareAtPrice $compare_at_price)
    {
        $this->compare_at_price = $compare_at_price;
        return $this;
    }


}