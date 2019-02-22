<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/21/19
 * Time: 10:54 AM
 */

namespace App\Http\Controllers;


use App\Model\Connection;
use App\Model\ShopOwnerConnection;
use Illuminate\Http\Request;

class ShopOwnerConnectionController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function store()
    {
        $data = [
            'shopOwner' => $this->shopOwner,
            'connections' => Connection::all()
        ];
        $failed = "";
        $selectedConnection = $this->request->input('selected');
        if (is_array($selectedConnection) && !empty($selectedConnection)) {

            $selectedId = array_search('on', $selectedConnection);
            $numberOfSelectedConnections = array_count_values($selectedConnection);

            if ($numberOfSelectedConnections['on'] > 1){
                $failed = "Can only have one selected connection.";
            } else if ($numberOfSelectedConnections === 0 ){
                $failed = "Please select a connection.";
            }

        }

        $message = "error";
        if (strlen($failed) > 0) {
            $data['msg'] = $failed;
        } else if (isset($selectedId)) {
            $shopOwnerConnection = new ShopOwnerConnection();
            $shopOwnerConnection->connection_id = $selectedId;
            $shopOwnerConnection->shop_owner_id = $this->shopOwner->id;
            if ($shopOwnerConnection->save() && $this->saveCredentials($selectedId)) {
                $data['msg'] = "Settings successfully updated.";
                $message = "success";

            } else {
                $data['msg'] = "Something went wrong.";
                $message = "error";
            }
        } else {

            $this->shopOwner->selectedConnections()->delete();
            $data['msg'] = "";
            $message = "error";
        }

        return redirect('/connections?shop=' . $this->shopOwner->name . '&' . $message . '=' . $data['msg']);

    }

    private function saveCredentials($selectedId)
    {
        /** @var ShopOwnerConnection $shopOwnerConnection */
        $shopOwnerConnection = ShopOwnerConnection::where([
            ['shop_owner_id', '=', $this->shopOwner->id],
            ['connection_id', '=', $selectedId],
        ])->first();
        if (isset($shopOwnerConnection)) {
            $shopOwnerConnection->client_id = $this->request->input('client_id');
            $shopOwnerConnection->client_secret = $this->request->input('client_secret');
            return $shopOwnerConnection->save();
        }
        return false;
    }
}