<?php

namespace App\Manager\Shopify;


class Location extends Shopify
{
    const SINGULAR_NAME = 'location';
    const PLURAL_NAME = 'locations';

    protected $address1;
    protected $address2;
    protected $city;
    protected $country;
    protected $country_code;
    protected $created_at;
    protected $id;
    protected $legacy;
    protected $name;
    protected $phone;
    protected $province;	
    protected $province_code;
    protected $updated_at;
    protected $zip;

    public function getPluralName()
    {
       return self::PLURAL_NAME;
    }

    public function getSingularName()
    {
       return self::SINGULAR_NAME;
    }

    public function load($location)
    {
        parent::process($location);
        return $this;
    }

    public function toEloquent($shopOwnerId)
    {
        $eloquent = \App\Model\Location::where('id', $this->getId())
            ->first();

        if (! isset($eloquent)) {
            $eloquent = new \App\Model\Location();
            $eloquent->id = $this->getId();
        }

        $eloquent->meta = json_encode($this);
        $eloquent->shop_owner_id = $shopOwnerId;

        return $eloquent;
    }

    /**
     * @return mixed
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param mixed $address1
     * @return Location
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param mixed $address2
     * @return Location
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Location
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Location
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param mixed $country_code
     * @return Location
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
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
     * @return Location
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
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
     * @return Location
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLegacy()
    {
        return $this->legacy;
    }

    /**
     * @param mixed $legacy
     * @return Location
     */
    public function setLegacy($legacy)
    {
        $this->legacy = $legacy;
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
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return Location
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     * @return Location
     */
    public function setProvince($province)
    {
        $this->province = $province;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvinceCode()
    {
        return $this->province_code;
    }

    /**
     * @param mixed $province_code
     * @return Location
     */
    public function setProvinceCode($province_code)
    {
        $this->province_code = $province_code;
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
     * @return Location
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     * @return Location
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }


}