<?php
namespace App\Manager\Shopify\Product\Image;

use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Shopify\AbstractMetafield;

class Metafield extends AbstractMetafield
{

    protected $product_id ;
    protected $image_id ;

    /**
     * @return string
     */
    public function getSingularName()
    {
        return 'metafield';
    }

    /**
     * @return string
     */
    public function getPluralName()
    {
        return 'metafields';
    }

    /**
     * @return string
     */
    public function url()
    {
        if (strlen( $this->getShop() ) === 0 || strlen( $this->getOwnerId() ) === 0 ) return '';
        return $this->restAdminUri() . 'metafields.json?metafield[owner_id]=' .
            $this->getOwnerId() . '&metafields[owner_resource]=product_image';
    }

    /**
     * @return null
     */
    public function fetch()
    {
        $httpConnect = new Client();

        $response = $httpConnect->request($this->url(), [], 'GET', $this->headers());
        $this->httpCode = $httpConnect->getHttpCode();
        $response = is_string($response) ? json_decode($response) : $response;

        $this->setResults($response);
        if ( ! is_null( Assist::getProperty($response, $this->getPluralName()) ) ) {
            return $response->{$this->getPluralName()};
        }

        return null;

    }

    public function store( $metafields )
    {
        $url             = "https://" . $this->getShop() . "/admin/products/" . $this->getProductId() . "/images/" . $this->getImageId() . ".json" ;
        $httpConnect    = new Client();
        $data           =  [ 'image' => [ 'id'=>$this->getImageId() , 'metafields' => $metafields] ];
        $response       = $httpConnect->request( $url , $data , 'PUT', $this->headers());
        $this->httpCode = $httpConnect->getHttpCode();
        $response       = is_string($response) ? json_decode($response) : $response;

        return $response;
    }

    /**
     * @return mixed
     */
    public function remove()
    {

        $uri =  $this->restAdminUri() . 'products' . DIRECTORY_SEPARATOR . $this->getProductId() . DIRECTORY_SEPARATOR .
            'images' . DIRECTORY_SEPARATOR . $this->getImageId() . DIRECTORY_SEPARATOR . 'metafields' .
            DIRECTORY_SEPARATOR . $this->getId() . '.json';

        $httpConnect = new Client();
        $response = $httpConnect->request($uri, [], 'DELETE', $this->headers());
        $this->httpCode = $httpConnect->getHttpCode();
        $response = is_string($response) ? json_decode($response) : $response;

        $this->setResults($response);

        if (Assist::getProperty($response, 'errors')) {
            $this->addError($response->errors);
            return $response->errors;
        }
        $response = !is_null(Assist::getProperty($response, $this->getPluralName())) ? $response->{$this->getPluralName()} : $response;
        return $response;
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
     * @return Metafield
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageId()
    {
        return $this->image_id;
    }

    /**
     * @param mixed $image_id
     * @return Metafield
     */
    public function setImageId($image_id)
    {
        $this->image_id = $image_id;
        return $this;
    }


}