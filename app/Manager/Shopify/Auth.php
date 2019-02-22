<?php

namespace App\Manager\Shopify;

use App\Manager\Basic\Client;

class Auth
{

    private static function adminUrl( $shop )
    {
        return 'https://' . $shop . '/admin/';
    }

    public static function createAuthRequest( $shop , $scopes, $redirectUrl = null)
    {

        if( strlen( $shop ) === 0 || ! isset( $shop ) || ! isset( $redirectUrl ) ) return '';

        $key    = env( 'SHOPIFY_KEY' ) ;

        if( strlen( $key ) === 0 || ! isset( $key ) ) return '';


        if (is_array($scopes)) $scopes = join(',', $scopes);

        return self::adminUrl( $shop ) . 'oauth/authorize?client_id=' . $key. '&redirect_uri=' . $redirectUrl . "&scope=$scopes";
    }

    public static function getAccessToken( $shop )
    {
        $secret = env( 'SHOPIFY_SECRET' ) ;
        $key    = env( 'SHOPIFY_KEY' ) ;

        $data = [
            'client_id'     => $key,
            'client_secret' => $secret,
            'code'          => $_GET['code']
        ];

        $headers = [
            'Content-type: application/json'
        ];

        $uri      = self::adminUrl( $shop ) . 'oauth/access_token';
        $client   = new Client();
        $response = $client->request( $uri , $data , 'POST' , $headers ) ;

        $response = is_string( $response ) ? json_decode( $response ) : $response ;

        return is_object( $response ) && property_exists( $response , 'access_token' ) ? $response->access_token : null;

    }

    public static function verify()
    {
        $data   = $_GET;
        $params = array();

        foreach($data as $param => $value) {
            if ($param != 'signature' && $param != 'hmac' ) {
                $params[$param] = "{$param}={$value}";
            }
        }

        asort($params);
        $params = implode('&', $params);

        $hmac           = array_key_exists( 'hmac' , $data ) ? $data['hmac'] : null ;
        $calculatedHmac = hash_hmac('sha256', $params, env( 'SHOPIFY_SECRET' ) );

        // if the hmac was not found then check to see if the request is coming from the app
        if (
            strlen($hmac) === 0
            && array_key_exists('HTTP_REFERER', $_SERVER)
        ) {
            if (strpos($_SERVER['HTTP_REFERER'], 'hmac') !== false) {
                $url   = $_SERVER['HTTP_REFERER'];
                $parts = parse_url($url);
                parse_str($parts['query'], $query);
                $hmac = array_key_exists('hmac', $query)?$query['hmac']:null;
                if (
                    strpos($url, 'jookbot.com') !== false
                    && $hmac !== null
                ) {
                    return true;
                }
            }
        }

        return ($hmac == $calculatedHmac);
    }


}