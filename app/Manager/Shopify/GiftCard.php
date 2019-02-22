<?php


namespace App\Manager\Shopify;

use App\Manager\Basic\Assist;
use App\Manager\Basic\Client;
use App\Manager\Basic\Status;

class GiftCard extends Shopify
{
    const SINGULAR_NAME = 'gift_card';
    const PLURAL_NAME   = 'gift_cards';

    protected $api_client_id;
    protected $balance;
    protected $code;
    protected $created_at;
    protected $currency;
    protected $customer_id;
    protected $disabled_at;
    protected $expires_on;
    protected $id;
    protected $initial_value;
    protected $last_characters;
    protected $line_item_id;
    protected $note;
    protected $order_id;
    protected $template_suffix;
    protected $user_id	;
    protected $updated_at;

    protected $_response;
    protected $_errors = [];

    public function getSingularName()
    {
        return self::SINGULAR_NAME;
    }

    public function getPluralName(){
        return self::PLURAL_NAME;
    }

    /**
     * Searches for gift cards that match a supplied query. The following fields are indexed by search:
     *
     * created_at , updated_at , disabled_at , balance , initial_value , amount_spent , last_characters
     *
     * @param string $q
     * @param int $page
     * @param string $order
     * @param int $limit
     * @return mixed
     */
    public function search( $q = '' , $page = 1 , $order = '' , $limit = 250 )
    {
        $uri      = $this->restAdminUri() . $this->getPluralName() . DIRECTORY_SEPARATOR ."search.json?query=$q&page=$page&order=$order&limit=$limit";
        $client   = new Client();
        $response = $client->request( $uri , [ $this->getSingularName() => $this->getId() ] , 'GET' , $this->headers() ) ;
        $this->setResponse( $response ) ;

        $cards = is_string( $response ) ? json_decode( $response ) : $response ;
        $cards = is_object( $cards ) && property_exists( $cards , $this->getPluralName() ) ? $cards->{ $this->getPluralName() } : $cards ;

        switch ( $client->getHttpCode() ){
            case 200:
                // todo process objects
                break;
            default :
                $errors[] = $response ;
                $this->setErrors( $errors ) ;
                break;
        }

        return $cards;

    }

    /**
     * Disables a gift card. Disabling a gift card can't be undone.
     *
     * @return bool
     */
    public function disable()
    {
        $uri      = $this->restAdminUri() . $this->getPluralName() . DIRECTORY_SEPARATOR . $this->getId() . DIRECTORY_SEPARATOR . 'disable.json';
        $client   = new Client();
        $response = $client->request( $uri , [ $this->getSingularName() => $this->getId() ] , 'POST' , $this->headers() ) ;

        $this->setResponse( $response ) ;

        switch ( $client->getHttpCode() ){
            case 201 :

                $giftCard               = new \App\Model\GiftCard();
                $giftCard->shopify_id   = $response->{ $this->getSingularName() }->id;
                $giftCard->shopify_json = json_encode( $response->{ $this->getSingularName() } ) ;
                $giftCard->status       = Status::ARCHIVE;

                $giftCard->save();

                return true;
            default :
                $errors[] = $response ;
                $this->setErrors( $errors ) ;
                return false;
        }
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @param mixed $response
     * @return GiftCard
     */
    public function setResponse($response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiClientId()
    {
        return $this->api_client_id;
    }

    /**
     * @param mixed $api_client_id
     * @return GiftCard
     */
    public function setApiClientId($api_client_id)
    {
        $this->api_client_id = $api_client_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     * @return GiftCard
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return GiftCard
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return GiftCard
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return GiftCard
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * @param mixed $customer_id
     * @return GiftCard
     */
    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisabledAt()
    {
        return $this->disabled_at;
    }

    /**
     * @param mixed $disabled_at
     * @return GiftCard
     */
    public function setDisabledAt($disabled_at)
    {
        $this->disabled_at = $disabled_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpiresOn()
    {
        return $this->expires_on;
    }

    /**
     * @param mixed $expires_on
     * @return GiftCard
     */
    public function setExpiresOn($expires_on)
    {
        $this->expires_on = $expires_on;
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
     * @return GiftCard
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInitialValue()
    {
        return $this->initial_value;
    }

    /**
     * @param mixed $initial_value
     * @return GiftCard
     */
    public function setInitialValue($initial_value)
    {
        $this->initial_value = $initial_value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastCharacters()
    {
        return $this->last_characters;
    }

    /**
     * @param mixed $last_characters
     * @return GiftCard
     */
    public function setLastCharacters($last_characters)
    {
        $this->last_characters = $last_characters;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLineItemId()
    {
        return $this->line_item_id;
    }

    /**
     * @param mixed $line_item_id
     * @return GiftCard
     */
    public function setLineItemId($line_item_id)
    {
        $this->line_item_id = $line_item_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     * @return GiftCard
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param mixed $order_id
     * @return GiftCard
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
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
     * @return GiftCard
     */
    public function setTemplateSuffix($template_suffix)
    {
        $this->template_suffix = $template_suffix;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return GiftCard
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
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
     * @return GiftCard
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
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
     * @return GiftCard
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
     * @return GiftCard
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     * @return GiftCard
     */
    public function setResults($results)
    {
        $this->results = $results;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     * @return GiftCard
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param mixed $httpCode
     * @return GiftCard
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
        return $this;
    }


}