<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/20/19
 * Time: 4:23 PM
 */

namespace App\Http\Controllers;


use App\Manager\Basic\Status;
use App\Model\Product;
use App\Model\Variant;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function sentFromShopify(){
        $id = $this->request->input('id');
        if (isset($id)){
            return $this->show($id);
        }
    }

    public function show($id)
    {
        /** @var Product $product */
        $product = Product::find($id);

        $connectTo = "";

        if (isset($this->shopOwner->selectedConnections[0])) {
            $connectTo = $this->shopOwner->selectedConnections[0]->connection->name;
        }
        $data = [
            'shopOwner' => $this->shopOwner,
            'product' => $product,
            'connectedTo' => $connectTo
        ];

        if ($this->request->ajax()) {
            return $data;
        }
        return view('products/show', $data);
    }

    public function update($id)
    {
        /** @var Product $product */
        $product = Product::find($id);

        $connectTo = "";

        if (isset($this->shopOwner->selectedConnections[0])) {
            $connectTo = $this->shopOwner->selectedConnections[0]->connection->name;
        }

        $data = [
            'shopOwner' => $this->shopOwner,
            'product' => $product,
            'connectedTo' => $connectTo
        ];

        $variants = $this->request->input('variants');
        $numberUpdated = 0;
        $errors = "";
        if (is_array($variants) && !empty($variants)) {
            foreach ($variants as $id => $inventoryValue) {
                /** @var Variant $v */
                $v = Variant::find($id);
                if (isset($v)) {
                    $updated = $v->inventoryLevel()->update([
                        'available' => (int)$inventoryValue,
                        'status' => Status::QUEUED
                    ]);
                    if ($updated) {
                        $shopify = $v->inventoryLevel->toShopify();
                        $shopify->set();

                        if ($shopify->httpCode === 200){
                            $numberUpdated++;
                        } else {

                            $results = is_string($shopify->getResults())
                                ? json_decode($shopify->getResults())
                                : $shopify->getResults();
                            if (is_object($results)
                            && property_exists($results, 'errors')){
                                $errors = is_array($results->errors) && array_key_exists(0, $results->errors)
                                ? $results->errors[0] : $results->errors;
                            }
                        }
                    }
                }
            }
        }

        if (!is_string($errors)) {
            $errors = json_encode($errors);
        }

        if (strlen($errors) > 0){
            $data['errors'] = $errors;
        } else if($numberUpdated === count($variants)){
            $data['success'] = "Inventory Successfully Updated.";
        }
        return view("products/show", $data);
    }
}