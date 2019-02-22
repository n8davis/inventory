<?php

namespace App\Manager\Shopify\Product;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Shopify\AbstractObject;
use App\Manager\Shopify\Product\Image\Metafield;

class Image extends AbstractObject
{

    protected $metafields;
    protected $created_at;
    protected $id;
    protected $position;
    protected $product_id;
    protected $variant_ids;
    protected $src;
    protected $alt;
    protected $width;
    protected $height;
    protected $updated_at;
    protected $admin_graphql_api_id;

    protected $_shop;
    protected $_token;
    protected $_results;

    public $httpCode;

    public function load($image)
    {
        parent::process($image);
        return $this;
    }

    private function getPluralName()
    {
        return 'images';
    }

    private function getSingularName()
    {
        return 'image';
    }

    public function restAdminUri()
    {
        return 'https://' . $this->getShop() . '/admin/' ;
    }

    public function headers()
    {
        return [
            "Content-Type: application/json",
            "X-Shopify-Access-Token: " . $this->getToken()
        ];
    }

    public function save( $id )
    {

        $uri         = 'https://' . $this->getShop() . '/admin/products/' . $id . '/' . $this->getPluralName() . '.json';
        $httpConnect = new Client();
        $response    = $httpConnect->request( $uri ,[ $this->getSingularName() =>$this  ], 'POST', $this->headers() );

        $this->httpCode = $httpConnect->getHttpCode() ;

        $this->setResults( $response );

        $response = is_string( $response ) ? json_decode( $response ) : $response ;

        if( Assist::getProperty( $response , $this->getSingularName() ) ) $this->setResults( $response->{ $this->getSingularName() } ) ;

        return $response;
    }

    public function update( $id , $productId  )
    {

        $uri = 'https://' . $this->getShop() . '/admin/products/' .
            $productId . '/' . $this->getPluralName() . "/$id" . '.json';

        $httpConnect = new Client();
        $response    = $httpConnect->request( $uri ,[ $this->getSingularName() =>$this  ], 'PUT', $this->headers() );

        $this->httpCode = $httpConnect->getHttpCode() ;

        $this->setResults( $response );

        $response = is_string( $response ) ? json_decode( $response ) : $response ;

        if( Assist::getProperty( $response , $this->getSingularName() ) ) $this->setResults( $response->{ $this->getSingularName() } ) ;

        return $response;

    }

    public function delete()
    {
        if ($this->getId() > 0) {
            $uri = 'https://' . $this->getShop() . '/admin/products/' .
                $this->getProductId() . '/' . $this->getPluralName() . "/" .
                $this->getId() . '.json';

            $httpConnect = new Client();
            $response = $httpConnect->request($uri, [], 'DELETE', $this->headers());

            $this->httpCode = $httpConnect->getHttpCode();

            return $response;
        }

        return false;

    }

    public function get()
    {

        $uri = 'https://' . $this->getShop() . '/admin/products/' .
            $this->getProductId() . '/' . $this->getPluralName() . "/" . $this->getId() . '.json';

        $httpConnect = new Client();
        $response    = $httpConnect->request( $uri ,[], 'GET', $this->headers() );

        $this->httpCode = $httpConnect->getHttpCode() ;

        $this->setResults( $response );

        $response = is_string( $response ) ? json_decode( $response ) : $response ;

        if( Assist::getProperty( $response , $this->getSingularName() ) ) $this->setResults( $response->{ $this->getSingularName() } ) ;

        return $response;

    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->_results;
    }

    /**
     * @param mixed $results
     * @return Image
     */
    public function setResults($results)
    {
        $this->_results = $results;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdminGraphqlApiId()
    {
        return $this->admin_graphql_api_id;
    }

    /**
     * @param mixed $admin_graphql_api_id
     * @return Image
     */
    public function setAdminGraphqlApiId($admin_graphql_api_id)
    {
        $this->admin_graphql_api_id = $admin_graphql_api_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param mixed $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
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
     * @return Image
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
     * @return Image
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Image
     */
    public function setPosition($position)
    {
        $this->position = $position;
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
     * @return Image
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVariantIds()
    {
        return $this->variant_ids;
    }

    /**
     * @param mixed $variant_ids
     * @return Image
     */
    public function setVariantIds($variant_ids)
    {
        $this->variant_ids = $variant_ids;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param mixed $src
     * @return Image
     */
    public function setSrc($src)
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;
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
     * @return Image
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @param int $variantId
     * @return $this
     */
    public function addVariantId( $variantId ){
        $ids = $this->getVariantIds();
        $ids[] = $variantId;
        $this->setVariantIds( $ids ) ;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->_shop;
    }

    /**
     * @param mixed $shop
     * @return Image
     */
    public function setShop($shop)
    {
        $this->_shop = $shop;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * @param mixed $token
     * @return Image
     */
    public function setToken($token)
    {
        $this->_token = $token;
        return $this;
    }

    /**
     * @return Image\Metafield[]
     */
    public function getMetafields()
    {
        return $this->metafields;
    }

    /**
     * @param Image\Metafield[] $metafields
     * @return Image
     */
    public function setMetafields($metafields)
    {
        $this->metafields = $metafields;
        return $this;
    }

    /**
     * @param Image\Metafield $metafield
     * @return $this
     */
    public function addMetafield( Image\Metafield $metafield )
    {
        $metafields = $this->getMetafields();
        $metafields[] = $metafield;
        $this->setMetafields( $metafields ) ;
        return $this;
    }
}