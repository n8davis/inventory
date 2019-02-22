<?php

namespace App\Manager\Basic;


class Assist
{

    const CONSOLE_COLOR_DEFAULT = '1;39m';
    const CONSOLE_COLOR_BLACK = '1;30m';
    const CONSOLE_COLOR_RED = '1;31m';
    const CONSOLE_COLOR_GREEN = '1;32m';
    const CONSOLE_COLOR_YELLOW = '1;33m';
    const CONSOLE_COLOR_BLUE = '1;34m';
    const CONSOLE_COLOR_MAGENTA = '1;35m';
    const CONSOLE_COLOR_CYAN = '1;36m';
    const CONSOLE_COLOR_LIGHT_GREY = '1;37m';
    const CONSOLE_COLOR_DARK_GREY = '1;90m';
    const CONSOLE_COLOR_LIGHT_RED = '1;91m';
    const CONSOLE_COLOR_LIGHT_GREEN = '1;92m';
    const CONSOLE_COLOR_LIGHT_YELLOW = '1;93m';
    const CONSOLE_COLOR_LIGHT_BLUE = '1;94m';
    const CONSOLE_COLOR_LIGHT_MAGENTA = '1;95m';
    const CONSOLE_COLOR_LIGHT_CYAN = '1;96m';
    const CONSOLE_COLOR_WHITE = '1;97m';
    /**
     * @param $subject
     * @param $message
     * @param array $emails
     * @return bool|null
     */
    public static function email( $subject , $message , array $emails ){
        $headers = [
            "From: " . env( 'MY_EMAIL' ),
            "Reply-To: " . env( 'MY_EMAIL' )
        ];
        $result = null;
        foreach ($emails as $email) $result = mail($email, $subject , $message, implode("\r\n", $headers));
        return $result;
    }
    public static function setter( $value )
    {
        $method = 'set' . ucwords(  $value, '_' );
        return str_replace( '_', '', $method );
    }

    public static function getter( $value )
    {
        $method = 'get' . ucwords(  $value, '_' );
        return str_replace( '_', '', $method );
    }

    /**
     * @param string $name
     * @return null
     */
    public static function getParam($name = ''){

        if (isset($_POST[$name]))  return $_POST[$name];

        if (isset($_GET[$name])) return $_GET[$name];

        if (empty( $name )) {
            return $_REQUEST;
        }
        return null;
    }

    /**
     * @param $object
     * @param bool $toJson
     * @return array|string
     */

    public static function convertToArray( $object , $toJson = FALSE  ) {
        $send             = [];
        $propertiesToSkip = [ 'shop' , 'access_token' ];
        if( is_object( $object ) ) {
            $newArray  = ( array ) $object ;
            foreach ($newArray  as $property => $values ) {
                if( ! is_null( $values ) ) :
                    $property =  preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\x9F\*]/u','', $property ) ;
                    if( is_array( $values ) ) :
                        foreach( $values as $index => $field ) :
                            if( is_numeric( $index ) ) :
                                $send[ $property ][] = self::convertToArray( $field );
                            else:
                                $send[ $property ][] = self::convertToArray( [ $index  =>  $field ] );
                            endif;
                        endforeach;
                    elseif ( in_array( $property , $propertiesToSkip ) ): continue;
                    else:
                        if( is_object( $values) ) {
                            $send[ $property ] = self::convertToArray( $values );
                        }
                        else{
                            $send[ $property ] = $values ;
                        }
                    endif;
                endif;
            }
        }
        else{
            return $object ;
        }
        if( $toJson == true ) return json_encode( $send ) ;
        return $send ;
    }

    public static function objArraySearch($array, $index, $value)
    {
        foreach($array as $arrayInf) {
            if($arrayInf->{$index} == $value) {
                return $arrayInf;
            }
        }
        return null;
    }

    /**
     * @param $object
     * @param $property
     * @return mixed|null
     */
    public static function getProperty( $object , $property ){
        if( is_array( $object ) ) :
            return array_key_exists( $property , $object ) ? $object[ $property ] : null ;
        elseif( is_object( $object ) ) :
            return property_exists( $object , $property) ? $object->{ $property } : null;
        endif;
    }

    /**
     * @param $data
     * @param bool $dump
     */
    public static function display( $data , $dump = false )
    {
        if( ! defined( 'ISCLI' ) ) define( 'ISCLI', PHP_SAPI === 'cli' );
        echo ISCLI ? "\r\n" : '<pre>';

        if ($dump){
            var_dump($data);
        } else {
            print_r($data);
        }

        echo ISCLI ? "\r\n" : '</pre>';

    }

    /**
     * @param $FullStr
     * @param $needle
     * @return bool
     */
    public static function endsWith($FullStr, $needle)
    {
        $StrLen     = strlen($needle);
        $FullStrEnd = substr($FullStr, strlen($FullStr) - $StrLen);
        return $FullStrEnd == $needle;
    }

    /**
     * @param $key
     * @param $envContents
     */
    public static function getEnvValue( $key , $envContents ) {

    }

    /**
     * @param $string
     * @return bool
     */
    public static function isValidAppString( $string ) {
        return strlen( $string ) > 0 && ! is_null( $string ) ? $string : false;
    }

    /**
     * @param array $charsToReplace
     * @param array $charsToAdd
     * @param $string
     * @return bool|mixed
     */
    public static function replaceCharsInString( array $charsToReplace , array $charsToAdd , $string ){
        if( strlen( $string ) === 0 ) return false;
        if( empty( $charsToReplace ) ) return false;
        if( ! is_array( $charsToReplace ) ) return false;
        if( empty( $charsToAdd ) ) return false;
        if( ! is_array( $charsToAdd ) ) return false;
        return str_replace( $charsToReplace, $charsToAdd, $string );
    }

    /**
     * @param int $size
     * @return string
     */
    public static function generateMemberKey( $size = 50 ){
        $chars    = "abcdefghjknpqrstwxyzABCDEFGHJKLMQSTUVWXYZ23456789";
        $memberKey = "";
        while ( strlen( $memberKey ) <= $size ) {
            $memberKey .= $chars[ mt_rand( 0, strlen( $chars ) - 1 ) ];
        }
        return $memberKey;
    }

    /**
     * @param $string
     * @return string
     */
    public static function cleanString( $string ){
        $returnedString = filter_var( $string , FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $returnedString = htmlspecialchars( $returnedString , ENT_QUOTES, 'UTF-8');
        return trim( $returnedString );
    }

    public static function cliShopName( $argv ) {
        if( ! array_key_exists( 1 , $argv ) ) return '';
        if( strpos( $argv[ 1 ] , 'shop=' ) === FALSE ) return '';
        $split = explode('=' , $argv[ 1 ] );
        if( ! array_key_exists( 1 , $split ) ) return '';
        return $split[ 1 ];

    }

    public static function cliFileId( $argv ) {
        if( ! array_key_exists( 2 , $argv ) ) return '';
        if( strpos( $argv[ 2 ] , 'fileId=' ) === FALSE ) return '';
        $split = explode('=' , $argv[ 2 ] );
        if( ! array_key_exists( 1 , $split ) ) return '';
        return $split[ 1 ];

    }


    public static function consoleLog( $data , $color = '1;34m')
    {
        if( is_string( $data ) ) echo "\033[" . $color . $data  . "\033[0m \n";
        else var_dump( $data ) ;
        return true;
    }
}