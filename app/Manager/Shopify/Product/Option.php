<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 10/11/18
 * Time: 9:30 AM
 */

namespace App\Manager\Shopify\Product;


use App\Manager\Shopify\AbstractObject;

class Option extends AbstractObject
{

    protected $id;
    protected $product_id;
    protected $name;
    protected $position;
    protected $values;

    public function process( $option )
    {
        if( ! is_object( $option ) ) return null;

        $this->setId( $this->getProperty( $option , 'id' ) )
            ->setProductId( $this->getProperty( $option , 'product_id' ) )
            ->setName( $this->getProperty( $option , 'name' ) )
            ->setPosition( $this->getProperty( $option , 'position' ) )
            ->setValues( $this->getProperty( $option , 'values' ) );

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Option
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param mixed $product_id
     * @return Option
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Option
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     * @return Option
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param mixed $values
     * @return Option
     */
    public function setValues($values)
    {
        $this->values = $values;
        return $this;
    }

}