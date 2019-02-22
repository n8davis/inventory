<?php
namespace App\Manager\Shopify\Order;


use App\Manager\Shopify\AbstractObject;

class NoteAttribute extends AbstractObject
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
     * @return NoteAttribute
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
     * @return NoteAttribute
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }



}