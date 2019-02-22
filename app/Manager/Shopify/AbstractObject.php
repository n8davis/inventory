<?php

namespace App\Manager\Shopify;


use App\Manager\Basic\Assist;

class AbstractObject implements \JsonSerializable
{
    public function process($data)
    {
        if (is_object($data)) {

            foreach(get_object_vars($data) as $property => $value ){
                if (is_object($value) || is_array($value)) {
                    continue;
                }
                $setter = Assist::setter($property);
                if (method_exists($this, $setter)) {
                    $this->{$setter}($value);
                }
            }
        }

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $data = [];
        $skip = [ 'shop' , 'access_token' , 'httpCode' , 'results' , 'errors' ];
        foreach( get_object_vars($this) as $key => $value ){

            if( in_array( $key , $skip ) || $key[ 0 ] === '_' ) continue;
            if( ( empty( $value ) || is_null( $value ) ) && $value !== 0 ) continue; 

            $method = 'get' . ucwords( $key, '_' );
            $method = str_replace( '_', '', $method );

            if( ! method_exists( $this , $method ) ) continue;

            $data[ $key ] = $this->{ $method }( $value );
        }
        return $data;
    }

    /**
     * @param $object
     * @param $property
     * @return null
     */
    public function getProperty( $object , $property )
    {
        if( ! is_object( $object ) ) return null;

        return property_exists( $object , $property ) ? $object->{ $property } : null;
    }

    public function setup( $object )
    {
        if( ! is_object( $object ) ) return null;
        foreach( get_object_vars( $this ) as $property => $value ){

            $setter = Assist::setter( $property ) ;

            if( method_exists( $this , $setter ) ){

                $val = property_exists( $object , $property ) ? $object->{ $property } : null ;
                $this->{ $setter }( $val ) ;
            }

        }

        return $this;
    }

}