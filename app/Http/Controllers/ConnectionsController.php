<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/21/19
 * Time: 10:10 AM
 */

namespace App\Http\Controllers;


use App\Model\Connection;
use Illuminate\Http\Request;

class ConnectionsController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $data = [
            'shopOwner' => $this->shopOwner,
            'connections' => Connection::all()
        ];
        if ( strlen($this->request->input('success')) > 0) {
            $data['success'] = $this->request->input('success');
        } else if ( strlen($this->request->input('error')) > 0 ) {
            $data['error'] = $this->request->input('error');
        }
        return view('connections', $data);
    }

}