<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
class AuthMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        $currentShop = $request->input( 'shop' ) ;

        if( strlen( $currentShop ) === 0 ) {
            abort(422);
        }

        $shop = \App\Model\ShopOwner::where( 'name', $currentShop )->first();

        if( ! isset( $shop ) ){

            $scopes      = env( 'SCOPES' );
            $redirectUrl = rawurlencode( env( 'APP_URL' ) . 'install' );
            return redirect( \App\Manager\Shopify\Auth::createAuthRequest( $currentShop, $scopes , $redirectUrl ) , 302 , [] , true ) ;

        }

        $response = $next( $request ) ;
        return $response ;


    }
}