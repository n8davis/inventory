<?php

namespace App\Manager\Shopify;


use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Basic\Logger;
use App\Manager\Basic\Status;
use App\Manager\Shopify\Product\Metafield;
use App\Manager\Shopify\Product\Option;
use App\Manager\Shopify\Product\Variant;
use App\Manager\Shopify\Product\Image;
use Illuminate\Database\QueryException;

class Product extends Shopify
{

    const SINGULAR_NAME = 'product';
    const PLURAL_NAME   = 'products';

    protected $body_html;
    protected $created_at;
    protected $handle;
    protected $id;
    protected $image;
    protected $images;
    protected $options;
    protected $product_type;
    protected $price;
    protected $published_at;
    protected $published_scope;
    protected $tags;
    protected $template_suffix;
    protected $title;
    protected $metafields = [];
    protected $metafields_global_title_tag;
    protected $metafields_global_description_tag;
    protected $updated_at;
    protected $variants;
    protected $vendor;
    protected $admin_graph_ql_api_id;

    public function getSingularName(){
        return self::SINGULAR_NAME;
    }

    public function getPluralName()
    {
        return self::PLURAL_NAME;
    }

    public function _responseHandler($response)
    {


    }

    public function load($product){
        $variants = [];
        $images = [];
        if (is_object($product) && property_exists($product, 'variants')) {
            $variants = $product->variants;
        }
        if (is_object($product) && property_exists($product, 'images')) {
            $images = $product->images;
        }
        if ( !empty($variants) ){
            foreach ($variants as $variant) {
                $v = new Variant();
                $this->addVariant($v->load($variant));
            }
        }
        if ( !empty($images) ){
            foreach ($images as $image) {
                $img = new Image();
                $this->addImage($img->load($image));
            }
        }

        parent::process($product);
        return $this;
    }

    public function toEloquent($shopOwnerId = null)
    {
        $eloquent = \App\Model\Product::where('id', $this->getId())->first();

        if (!isset($eloquent)) {
            $eloquent = new \App\Model\Product();
            $eloquent->id = $this->getId();
        }

        $eloquent->title = $this->getTitle();
        $eloquent->meta = json_encode($this);
        $eloquent->shop_owner_id = $shopOwnerId;
        $eloquent->status = Status::PENDING;

        return $eloquent;
    }

    /**
     * @return $this
     */
    public function removeImages()
    {
        unset( $this->image );
        unset( $this->images );
        return $this;
    }

    /**
     * @return $this
     */
    public function removeVariants()
    {
        unset( $this->variants );
        return $this;
    }

    public function uploadImages()
    {
        $url = "https://" . $this->getShop() . "/admin/products/" . $this->getId() . "/images.json";
        $client = new Client();

        if( ! is_array( $this->getImages() ) ) return false;

        $httpCodes = [];
        foreach( $this->getImages() as $image ){
            $response = $client->request( $url , [ 'image' => $image ] , 'POST' , $this->headers() );
            dd( $response ) ;

            $httpCodes[] = $client->getHttpCode();

        }

        return in_array( 200 , $httpCodes ) ? true : false;

    }

    /**
     * Shopify JSON to Product Object
     *
     * @param $product
     * @return Product
     */
    public function process( $product )
    {
        if( ! is_object( $product ) ) return null;

        if( property_exists( $product , 'product' ) ) $product = $product->product;

        $variants   = is_null( $variantProperty = $this->getProperty( $product , 'variants' ) ) ? [] : $variantProperty;
        $metafields = is_null( $metafieldProperty = $this->getProperty( $product , 'metafields' ) ) ? [] : $metafieldProperty;
        $options    = is_null( $optionProperty = $this->getProperty( $product , 'options' ) ) ? [] : $optionProperty;
        $images     = is_null( $imageProperty = $this->getProperty( $product , 'images' ) ) ? [] : $imageProperty;

        foreach( $variants as $variant ){
            $shopifyVariant = new Variant();
            $this->addVariant( $shopifyVariant->process( $variant ) ) ;
        }

        foreach( $options as $option ){
            $shopifyOption = new Option();
            $this->addOption( $shopifyOption->process( $option ) ) ;
        }

        foreach( $metafields as $metafield ){
            $productMetafield = new Metafield();
            $this->addMetafield( $productMetafield->process( $metafield ) ) ;
        }

        foreach( $images as $image ){
            $shopifyImage = new Image();
            $this->addImage( $shopifyImage->process( $image ) ) ;
        }

        $this->setId( $this->getProperty( $product , 'id' ) )
            ->setTitle( $this->getProperty( $product , 'title' ) )
            ->setPrice( $this->getProperty( $product , 'price' ) )
            ->setTags( $this->getProperty( $product , 'tags' ) )
            ->setAdminGraphQlApiId( $this->getProperty( $product , 'admin_graph_api_id' ) )
            ->setBodyHtml( $this->getProperty( $product , 'body_html' ) )
            ->setHandle( $this->getProperty( $product , 'handle' ) )
            ->setMetafieldsGlobalDescriptionTag( $this->getProperty( $product , 'metafields_global_description_tag' ) )
            ->setMetafieldsGlobalTitleTag( $this->getProperty( $product , 'metafields_global_title_tag' ) )
            ->setProductType( $this->getProperty( $product , 'product_type' ) )
            ->setPublishedAt( $this->getProperty( $product , 'published_at' ) )
            ->setPublishedScope( $this->getProperty( $product , 'published_scope' ) )
            ->setTemplateSuffix( $this->getProperty( $product , 'template_suffix' ) )
            ->setVendor( $this->getProperty( $product , 'vendor' ) );

        return $this;
    }

    public function removeMetafields()
    {
        unset( $this->metafields ) ;
        return $this; 
    }

    /**
     * @return Metafield[]
     */
    public function getMetafields()
    {
        return $this->metafields;
    }

    /**
     * @param Metafield[] $metafields
     * @return Product
     */
    public function setMetafields($metafields)
    {
        $this->metafields = $metafields;
        return $this;
    }

    public function addMetafield( Metafield $metafield )
    {
        $metafields = $this->getMetafields() ;
        if( ! in_array( $metafield ,$metafields ) ) $metafields[] = $metafield;
        $this->setMetafields( $metafields ) ;
        return $this; 
    }

    /**
     * @param \App\Model\Product\Metafield[] $metafields
     * @param $owner_id
     * @return null
     */
    public function addProductMetafields( $metafields , $owner_id )
    {
        if( empty( $metafields ) || ! is_array( $metafields ) ) return null;

        foreach( $metafields as $nonImportedMetafield ){

            if( ! is_null( $nonImportedMetafield->shopify_id ) || ( int ) $nonImportedMetafield->shopify_id > 0 )  continue;

            $unSyncedMetafield = new Metafield();
            $unSyncedMetafield->setOwnerId( $owner_id )
                ->setKey( $nonImportedMetafield->key )
                ->setNamespace( $nonImportedMetafield->namespace )
                ->setValue( $nonImportedMetafield->value )
                ->setValueType( $nonImportedMetafield->value_type ) ;

            Assist::consoleLog( "Adding Metafield Key $nonImportedMetafield->key Namespace $nonImportedMetafield->namespace") ;

            $this->addMetafield( $unSyncedMetafield );

        }
    }

    /**
     * @param array|\App\Model\Product\Metafield[] $metafields
     * @return null
     */
    public function updateProductMetafields( $metafields )
    {
        if( empty( $metafields ) || ! is_array( $metafields ) ) return null;

        foreach( $metafields as $metafield ){
            $shopify = new Metafield();
            $shopify->setAccessToken( $this->getAccessToken() )->setShop( $this->getShop() );
            $shopify->setOwnerId( $metafield->owner_id )
                ->setOwnerResource( $metafield->owner_resource )
                ->setId( $metafield->shopify_id )
                ->setValueType( $metafield->value_type )
                ->setValue( $metafield->value );
            $shopify->update( $metafield->shopify_id ) ;

            $message= "HTTP Code $shopify->httpCode Updating Metafield For Product "
                . $this->getTitle()
                . " with value "
                . $metafield->value;
            switch ($shopify->httpCode){
                case 404:
                    try{
                        if ($metafield->delete()) {
                            $message = "Metafield $metafield->key not found. Removed from DataBurst.";

                            $e = new Error();
                            $e->searchable_entity = $metafield->key;
                            $e->message = $message;
                            $e->shop_owner_id = $metafield->shopOwner;
                            $e->process_name = "Product Import";
                            $e->save();

                            Logger::writeToLogFile($message, 'products', $this->getShop());
                        }
                    } catch (QueryException $queryException){
                        $message = "Problem updating metafield with product id $metafield->owner_id.";
                        Logger::writeToLogFile( $message, 'products' , $this->getShop() );
                        Logger::writeToLogFile(
                            $message . PHP_EOL . $queryException->getMessage(),
                            'metafields',
                            'default'
                        );
                    } catch (\Exception $e){
                        $message = "Problem updating metafield with product id $metafield->owner_id.";
                        Logger::writeToLogFile( $message , 'products' , $this->getShop() );
                        Logger::writeToLogFile(
                            $message . PHP_EOL . $e->getMessage(),
                            'metafields',
                            'default'
                        );
                    }
                    break;
            }
            Assist::consoleLog( $message ) ;

        }
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdminGraphQlApiId()
    {
        return $this->admin_graph_ql_api_id;
    }

    /**
     * @param mixed $admin_graph_ql_api_id
     * @return Product
     */
    public function setAdminGraphQlApiId($admin_graph_ql_api_id)
    {
        $this->admin_graph_ql_api_id = $admin_graph_ql_api_id;
        return $this;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function addImage( Image $image ){
        $images = $this->getImages();
        $images[] = $image;
        $this->setImages( $images ) ;
        return $this;
    }

    /**
     * @param Variant $variant
     * @return $this
     */
    public function addVariant( Variant $variant ){
        $variants = $this->getVariants();
        $variants[] = $variant;
        $this->setVariants( $variants ) ;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBodyHtml()
    {
        return htmlspecialchars( $this->body_html ) ;
    }

    /**
     * @param mixed $body_html
     * @return Product
     */
    public function setBodyHtml($body_html)
    {
        $this->body_html = $body_html;
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
     * @return Product
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param mixed $handle
     * @return Product
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
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
     * @return Product
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Image[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     * @return Product
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    public function addOption( Option $option )
    {
        $options   = $this->getOptions() ;
        $options[] = $option;
        $this->setOptions( $options );
        return $this;
    }

    /**
     * @param Option[] $options
     * @return Product
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductType()
    {
        return $this->product_type;
    }

    /**
     * @param mixed $product_type
     * @return Product
     */
    public function setProductType($product_type)
    {
        $this->product_type = $product_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * @param mixed $published_at
     * @return Product
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublishedScope()
    {
        return $this->published_scope;
    }

    /**
     * @param mixed $published_scope
     * @return Product
     */
    public function setPublishedScope($published_scope)
    {
        $this->published_scope = $published_scope;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function getSpecificTag($specificTag, $tags = [])
    {
        if (empty($tags)) {
            $tags = $this->getTags();
        }
        $tags = explode(",", $tags);
        if (is_array($tags) && ! empty($tags)) {
            foreach ($tags as $tag) {
                if (strpos($tag, $specificTag) !== FALSE) {
                    $parseTag = explode(":", $tag);
                    if (is_array($parseTag) && array_key_exists(1, $parseTag)) {
                        return $parseTag[1];
                    }
                }
            }
        }

        return '';
    }

    /**
     * @param mixed $tags
     * @return Product
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplateSuffix()
    {
        return $this->template_suffix;
    }

    /**
     * @param mixed $template_suffix
     * @return Product
     */
    public function setTemplateSuffix($template_suffix)
    {
        $this->template_suffix = $template_suffix;
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
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetafieldsGlobalTitleTag()
    {
        return $this->metafields_global_title_tag;
    }

    /**
     * @param mixed $metafields_global_title_tag
     * @return Product
     */
    public function setMetafieldsGlobalTitleTag($metafields_global_title_tag)
    {
        $this->metafields_global_title_tag = $metafields_global_title_tag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetafieldsGlobalDescriptionTag()
    {
        return $this->metafields_global_description_tag;
    }

    /**
     * @param mixed $metafields_global_description_tag
     * @return Product
     */
    public function setMetafieldsGlobalDescriptionTag($metafields_global_description_tag)
    {
        $this->metafields_global_description_tag = $metafields_global_description_tag;
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
     * @return Product
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return Variant[]
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param Variant[] $variants
     * @return Product
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param mixed $vendor
     * @return Product
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     * @return Product
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param mixed $access_token
     * @return Product
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }


}