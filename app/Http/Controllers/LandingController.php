<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/20/19
 * Time: 3:27 PM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class LandingController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        if (strlen($this->request->input('q')) > 0) {
            $products = $this->shopOwner->products()->where([
                ['shop_owner_id', '=', $this->shopOwner->id],
                ['title', 'like', '%'.$this->request->input('q').'%'],
            ]);
        } else {
            $products = $this->shopOwner->products();
        }
        $data = [
            'shopOwner' => $this->shopOwner,
            'products' => $products->paginate(
                25, ['*'], 'page', $this->page()
            ),
            'page' => $this->page(),
            'search' => $this->request->input('q')
        ];

        $data['total_pages'] = $data['products']->lastPage();

        if ($data['page'] > $data['total_pages']) {
            $data['page'] = $data['total_pages'];
        }

        if ($data['page'] <= 1) {
            $data['page'] = 1;
        }

        if ($this->request->ajax()) {
            return $data;
        }

        return view( 'landing' , $data );
    }
}