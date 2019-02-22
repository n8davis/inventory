<?php

namespace App\Manager\Basic;


class Client
{

    protected $link_header ;
    public $http_code;

    private $_maxRetries = 10;

    public function parseHeaders($response)
    {
        $headers = array();

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0)
                $headers['http_code'] = $line;
            else {
                list ($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        }

        if( array_key_exists( 'Link' , $headers ) ) {
            $linkHeader = $headers[ 'Link' ];
            $this->setLinkHeader( $linkHeader );
        }

        return $headers;
    }

    /**
     * @param $uri
     * @param array $dataToPost
     * @param $type
     * @param $headers
     * @return mixed
     */
    public function request($uri, array $dataToPost, $type, $headers)
    {
        $numRetries = 0;
        $curl       = curl_init();
        $data       = null;

        if( ! empty( $dataToPost ) ) {
            $data = json_encode( $dataToPost ) ;
            $headers[] = 'Content-Length: ' . strlen( $data ) ;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data );
        }

        curl_setopt( $curl , CURLOPT_URL, $uri);
        curl_setopt( $curl , CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt( $curl , CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $curl , CURLOPT_HTTPHEADER, $headers ) ;
        curl_setopt( $curl , CURLOPT_VERBOSE, 0);
        curl_setopt( $curl , CURLOPT_HEADER, 0);
        curl_setopt( $curl , CURLOPT_FOLLOWLOCATION, '0');

        switch ( strtoupper( $type ) ){
            case 'PUT' :
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
            break;
            case 'GET':
                curl_setopt($curl, CURLOPT_POST, 0);
            break;
        }


        do {

            $results         = curl_exec($curl);
            $this->http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($this->http_code === 429) {
                $numRetries = $numRetries + 1;
                usleep(250000); // 1/4 of a second
            }

        } while( $this->http_code === 429 && $numRetries < $this->_maxRetries );

        $header_size     = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $info            = curl_getinfo($curl);
        $header          = substr($results, 0, $header_size);

        curl_close( $curl );

        return $results;
    }

    /**
     * @return mixed
     */
    public function getLinkHeader()
    {
        return $this->link_header;
    }

    /**
     * @param mixed $link_header
     * @return Client
     */
    public function setLinkHeader($link_header)
    {
        $this->link_header = $link_header;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->http_code;
    }

    /**
     * @param mixed $http_code
     * @return Client
     */
    public function setHttpCode($http_code)
    {
        $this->http_code = $http_code;
        return $this;
    }


}