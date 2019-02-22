<?php

namespace App\Manager\Shopify\Shop;


class PrimaryDomain
{

    protected $url;
    protected $host;

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return PrimaryDomain
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     * @return PrimaryDomain
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }


}