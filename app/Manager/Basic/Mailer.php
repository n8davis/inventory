<?php

namespace App\Manager\Basic;


class Mailer
{

    private $headers = [];
    public static function send( $to , $from , $message , $headers = [] )
    {
        $entity = new self;
        if( empty( $headers ) ) $headers = $entity->headers;

    }
}