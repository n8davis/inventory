<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/21/19
 * Time: 10:10 AM
 */

namespace App\Http\Controllers;


use App\Model\Configuration;
use App\Model\Connection;
use Illuminate\Http\Request;

class ConfigurationsController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $data = [
            'shopOwner' => $this->shopOwner,
            'configuration' => $this->shopOwner->configurations
        ];
        return view('configurations', $data);
    }

    public function store()
    {
        $inputs = $this->request->input();
        if (!empty($inputs)) {
            foreach( $inputs as $entity => $value) {
                $where = [
                    ['shop_owner_id', '=', $this->shopOwner->id],
                    ['entity', '=', $entity],
                ];
                /** @var Configuration $config */
                $config = Configuration::where($where)->first();
                if (isset($config)) {
                    $config->value = $value;
                    $config->save();
                }
            }
        }

        return redirect("/configurations?shop=" . $this->shopOwner->name );
    }

}