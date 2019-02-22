<?php

namespace App\Manager\Shopify;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;

abstract class AbstractMetafield extends Shopify
{

    /**
     * https://help.shopify.com/en/api/reference/metafield#properties
     * The metafield's information type. Valid values: string, integer, json_string.
     */
    const JSON_TYPE = 'json_string' ;

    /**
     * https://help.shopify.com/en/api/reference/metafield#properties
     * The metafield's information type. Valid values: string, integer, json_string.
     */
    const STRING_TYPE = 'string';

    /**
     * https://help.shopify.com/en/api/reference/metafield#properties
     * The metafield's information type. Valid values: string, integer, json_string.
     */
    const INTEGER_TYPE = 'integer';

    protected $created_at;
    protected $description;
    protected $id;
    protected $key;
    protected $namespace;
    protected $owner_id;
    protected $owner_resource;
    protected $value;
    protected $value_type;
    protected $updated_at;


    public function update( $id  ){
        $url = $this->restAdminUri() . 'metafields' . DIRECTORY_SEPARATOR . $id . '.json' ;
        $httpConnect    = new Client();
        $response       = $httpConnect->request( $url , [ $this->getSingularName() => $this ] , 'PUT', $this->headers() );

        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $httpCode = $httpConnect->http_code;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response , 'errors' ) || ( $httpCode <= 599 && $httpCode >= 400 ) ){
            $this->addError( $response );
            return false;
        }
        else{
            if(Assist::getProperty( $response , $this->getSingularName()  ) ){
                if( Assist::getProperty( $response->{ $this->getSingularName() }  , 'id' ) ){
                    return $response->{ $this->getSingularName() }->id;
                }
            }
            return false;
        }
    }

    public function process( $metafield )
    {
        if( ! is_object( $metafield ) ) return null;

        $this->setCreatedAt( $this->getProperty( $metafield , 'created_at' ) )
            ->setDescription( $this->getProperty( $metafield , 'description' ) )
            ->setId( $this->getProperty( $metafield , 'id' ) )
            ->setKey( $this->getProperty( $metafield , 'key' ) )
            ->setNamespace( $this->getProperty( $metafield , 'namespace' ) )
            ->setOwnerId( $this->getProperty( $metafield , 'owner_id' ) )
            ->setOwnerResource( $this->getProperty( $metafield , 'owner_resource' ) )
            ->setValue( $this->getProperty( $metafield , 'value' ) )
            ->setValueType( $this->getProperty( $metafield , 'value_type' ) )
            ->setUpdatedAt( $this->getProperty( $metafield , 'updated_at' ) );

        return $this;
    }

    /**
     *
     */
    public function create()
    {
        $httpConnect = new Client();

        $response = $httpConnect->request( $this->url() ,[ $this->getSingularName() => $this ], 'POST', $this->headers() );

        $this->httpCode = $httpConnect->getHttpCode() ;
        $response       = is_string( $response ) ? json_decode( $response ) : $response ;

        $this->setResults( $response ) ;

        if( Assist::getProperty( $response , 'errors' ) || ( $this->httpCode <= 599 && $this->httpCode >= 400 ) ){
            $this->addError( $response->errors ) ;
        }

        return $response;
    }

    public function getShopifyValueType()
    {
        if( is_integer( $this->value ) ) return self::INTEGER_TYPE ;

        if( is_string( $this->value ) ){
            $isJson  = is_object( json_decode( $this->value ) ) || is_array( json_decode( $this->value ) );
            return $isJson ? self::JSON_TYPE : self::STRING_TYPE;
        }

        return '';
    }

    public function removeNamespace()
    {
        unset( $this->namespace ) ;
        return $this;
    }

    public function removeKey()
    {
        unset( $this->key ) ;
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
     * @return AbstractMetafield
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
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
     * @return AbstractMetafield
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return AbstractMetafield
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return AbstractMetafield
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     * @return AbstractMetafield
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param mixed $owner_id
     * @return AbstractMetafield
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwnerResource()
    {
        return $this->owner_resource;
    }

    /**
     * @param mixed $owner_resource
     * @return AbstractMetafield
     */
    public function setOwnerResource($owner_resource)
    {
        $this->owner_resource = $owner_resource;
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
     * @return AbstractMetafield
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
     * @return AbstractMetafield
     */
    public function setValueType($value_type)
    {
        $this->value_type = $value_type;
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
     * @return AbstractMetafield
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }


}