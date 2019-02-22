<?php
namespace App\Http\Middleware;


use App\Manager\Basic\Assist;

class HmacMiddleware
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
        $response = $next($request);
        if( \App\Manager\Shopify\Auth::verify() === false ) {
//            abort(422);
        }

        return $response ;
    }
}