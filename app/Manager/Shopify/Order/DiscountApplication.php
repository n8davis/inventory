<?php

namespace App\Manager\Shopify\Order;


use App\Manager\Shopify\AbstractObject;

class DiscountApplication extends AbstractObject
{
    protected $type;
    protected $title;
    protected $description;
    protected $value;
    protected $value_type;
    protected $allocation_method;
    protected $target_selection;
    protected $target_type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return DiscountApplication
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @return DiscountApplication
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return DiscountApplication
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return DiscountApplication
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValueType()
    {
        return $this->value_type;
    }

    /**
     * @param mixed $value_type
     * @return DiscountApplication
     */
    public function setValueType($value_type)
    {
        $this->value_type = $value_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAllocationMethod()
    {
        return $this->allocation_method;
    }

    /**
     * @param mixed $allocation_method
     * @return DiscountApplication
     */
    public function setAllocationMethod($allocation_method)
    {
        $this->allocation_method = $allocation_method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetSelection()
    {
        return $this->target_selection;
    }

    /**
     * @param mixed $target_selection
     * @return DiscountApplication
     */
    public function setTargetSelection($target_selection)
    {
        $this->target_selection = $target_selection;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetType()
    {
        return $this->target_type;
    }

    /**
     * @param mixed $target_type
     * @return DiscountApplication
     */
    public function setTargetType($target_type)
    {
        $this->target_type = $target_type;
        return $this;
    }


}