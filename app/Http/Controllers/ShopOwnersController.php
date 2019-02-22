<?php
namespace App\Http\Controllers;

use App\Model\ShopOwner;
use Illuminate\Http\Request;

class ShopOwnersController extends Controller
{

    public function access( Request $request ){

        $shop = $request->input( 'shop' ) ;

        if( strlen( $shop ) === 0 ) {
            return response()->json(false, 200);
        }
        $shopOwner = new ShopOwner();

        /** @var ShopOwner $shopOwner */
        $shopOwner = $shopOwner->where( 'shop' , $shop )->first();

        if( ! isset( $shopOwner ) ) {
            return response()->json(false, 200);
        }
        $token = $shopOwner->token;
        return response()->json($token, 200);

    }

}