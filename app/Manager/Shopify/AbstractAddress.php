<?php

namespace App\Manager\Shopify;


abstract class AbstractAddress extends AbstractObject
{

    protected $address1;
    protected $address2;
    protected $city;
    protected $company;
    protected $country;
    protected $first_name;
    protected $id;
    protected $last_name;
    protected $phone;
    protected $province;
    protected $zip;
    protected $name;
    protected $province_code;
    protected $country_code;

    public function process( $address )
    {
        if( ! is_object( $address ) ) return null;

        $this->setAddress1( $this->getProperty( $address , 'address1' ) )
            ->setAddress2( $this->getProperty( $address , 'address2' ) )
            ->setCity( $this->getProperty( $address , 'city' ) )
            ->setCompany( $this->getProperty( $address , 'company' ) )
            ->setCountry( $this->getProperty( $address , 'country' ) )
            ->setCountryCode( $this->getProperty( $address , 'country_code' ) )
            ->setFirstName( $this->getProperty( $address , 'first_name' ) )
            ->setLastName( $this->getProperty( $address , 'last_name' ) )
            ->setId( $this->getProperty( $address , 'id' ) )
            ->setPhone( $this->getProperty( $address , 'phone' ) )
            ->setProvinceCode( $this->getProperty( $address , 'province_code' ) )
            ->setProvince( $this->getProperty( $address , 'province' ) )
            ->setZip( $this->getProperty( $address , 'zip' ) )
            ->setName( $this->getProperty( $address , 'name' ) );

        return $this;
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
     * @return AbstractAddress
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
     * @return AbstractAddress
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
     * @return AbstractAddress
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     * @return AbstractAddress
     */
    public function setCompany($company)
    {
        $this->company = $company;
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
     * @return AbstractAddress
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     * @return AbstractAddress
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
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
     * @return AbstractAddress
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     * @return AbstractAddress
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
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
     * @return AbstractAddress
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
     * @return AbstractAddress
     */
    public function setProvince($province)
    {
        $this->province = $province;
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
     * @return AbstractAddress
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
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
     * @return AbstractAddress
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return AbstractAddress
     */
    public function setProvinceCode($province_code)
    {
        $this->province_code = $province_code;
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
     * @return AbstractAddress
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
        return $this;
    }


}