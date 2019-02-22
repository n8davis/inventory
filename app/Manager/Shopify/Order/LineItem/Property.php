<?php

namespace App\Manager\Shopify\Order\LineItem;


class Property
{

    protected $name;
    protected $value;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Property
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Property
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


}