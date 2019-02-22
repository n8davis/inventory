<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
/**@var Laravel\Lumen\Routing\Router $router */
$router->get(
    '/',
    ['middleware' => [ 'auth', 'hmac' ],
        function ( \Illuminate\Http\Request $request ) {
            $controller = new \App\Http\Controllers\LandingController($request);
            return $controller->index();
        }
    ]
);
$router->post( '/shop-owner-connections' , 'ShopOwnerConnectionController@store' ) ;
$router->get( '/shopify-products/' , 'ProductsController@sentFromShopify' ) ;
$router->get( '/products/{id}' , 'ProductsController@show' ) ;
$router->put( '/products/{id}' , 'ProductsController@update' ) ;
$router->get( 'connections' , 'ConnectionsController@index' ) ;
$router->get( 'configurations' , 'ConfigurationsController@index' ) ;
$router->post( 'configurations' , 'ConfigurationsController@store' ) ;
$router->get( 'install' , 'ShopifyController@install' ) ;
$router->post('/webhooks', 'ShopifyController@webhooks' ) ;
$router->post( '/shops/access' , 'ShopOwnersController@access' ) ;
