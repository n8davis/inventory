<?php

namespace App\Manager\Shopify;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Manager;

class Webhook extends Shopify
{
    const SINGULAR_NAME = 'webhook';
    const PLURAL_NAME = 'webhooks';


    protected $address;
    protected $created_at;
    protected $format;
    protected $fields;
    protected $id;
    protected $metafield_namespaces;
    protected $topic;
    protected $updated_at;

    public function load($value)
    {
        parent::process($value);
        return $this;
    }

    public function getPluralName()
    {
        return self::PLURAL_NAME;
    }

    public function getSingularName()
    {
        return self::SINGULAR_NAME;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Webhook
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return Webhook
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     * @return Webhook
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param mixed $fields
     * @return Webhook
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
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
     * @return Webhook
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetafieldNamespaces()
    {
        return $this->metafield_namespaces;
    }

    /**
     * @param mixed $metafield_namespaces
     * @return Webhook
     */
    public function setMetafieldNamespaces($metafield_namespaces)
    {
        $this->metafield_namespaces = $metafield_namespaces;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param mixed $topic
     * @return Webhook
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     * @return Webhook
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }



}