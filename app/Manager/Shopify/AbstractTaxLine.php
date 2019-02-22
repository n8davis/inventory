<?php

namespace App\Manager\Shopify;


class AbstractTaxLine extends AbstractObject
{

    protected $title;
    protected $price;
    protected $rate;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return AbstractTaxLine
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @return AbstractTaxLine
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     * @return AbstractTaxLine
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }


}