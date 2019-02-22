<?php

namespace App\Manager\Shopify\Order;


use App\Manager\Shopify\AbstractObject;

class ClientDetail extends AbstractObject
{
    protected $accept_language;
    protected $browser_height;
    protected $browser_ip;
    protected $browser_width;
    protected $session_hash;
    protected $user_agent;

    public function process( $clientDetail )
    {
        if( ! is_object( $clientDetail ) ) return null;

        $this->setAcceptLanguage( $this->getProperty( $clientDetail , 'accept_language') )
            ->setBrowserHeight( $this->getProperty( $clientDetail , 'browser_height' ) )
            ->setBrowserIp( $this->getProperty( $clientDetail , 'browser_ip') )
            ->setBrowserWidth( $this->getProperty( $clientDetail , 'browser_width') )
            ->setSessionHash( $this->getProperty( $clientDetail , 'session_hash') )
            ->setUserAgent( $this->getProperty( $clientDetail , 'user_agent') );

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcceptLanguage()
    {
        return $this->accept_language;
    }

    /**
     * @param mixed $accept_language
     * @return ClientDetail
     */
    public function setAcceptLanguage($accept_language)
    {
        $this->accept_language = $accept_language;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserHeight()
    {
        return $this->browser_height;
    }

    /**
     * @param mixed $browser_height
     * @return ClientDetail
     */
    public function setBrowserHeight($browser_height)
    {
        $this->browser_height = $browser_height;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserIp()
    {
        return $this->browser_ip;
    }

    /**
     * @param mixed $browser_ip
     * @return ClientDetail
     */
    public function setBrowserIp($browser_ip)
    {
        $this->browser_ip = $browser_ip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserWidth()
    {
        return $this->browser_width;
    }

    /**
     * @param mixed $browser_width
     * @return ClientDetail
     */
    public function setBrowserWidth($browser_width)
    {
        $this->browser_width = $browser_width;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSessionHash()
    {
        return $this->session_hash;
    }

    /**
     * @param mixed $session_hash
     * @return ClientDetail
     */
    public function setSessionHash($session_hash)
    {
        $this->session_hash = $session_hash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * @param mixed $user_agent
     * @return ClientDetail
     */
    public function setUserAgent($user_agent)
    {
        $this->user_agent = $user_agent;
        return $this;
    }



}