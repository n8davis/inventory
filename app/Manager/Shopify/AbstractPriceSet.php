<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 10/24/18
 * Time: 12:52 PM
 */

namespace App\Manager\Shopify;

abstract class AbstractPriceSet extends AbstractObject
{

    protected $shop_money;
    protected $presentment_money;

    /**
     * @return mixed
     */
    public function getShopMoney()
    {
        return $this->shop_money;
    }

    /**
     * @param mixed $shop_money
     * @return AbstractPriceSet
     */
    public function setShopMoney($shop_money)
    {
        $this->shop_money = $shop_money;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPresentmentMoney()
    {
        return $this->presentment_money;
    }

    /**
     * @param mixed $presentment_money
     * @return AbstractPriceSet
     */
    public function setPresentmentMoney($presentment_money)
    {
        $this->presentment_money = $presentment_money;
        return $this;
    }


}