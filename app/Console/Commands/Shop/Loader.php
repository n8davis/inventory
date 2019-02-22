<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/19/19
 * Time: 3:57 PM
 */

namespace App\Console\Commands\Shop;


use App\Console\Commands\AbstractCommand;
use App\Manager\Shopify;

class Loader extends AbstractCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:loader {shopOwnerId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads shop data on install';

    /**
     * Loader constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(get_class($this));
        if ($this->setup($this->argument('shopOwnerId')) === false){
            return null;
        }
        $this->loadProducts();
//        $this->loadInventory();
    }

    private function loadProducts(){
        $number = 0;
        $page = 1;
        do{
            $shopify  = new Shopify\Product();
            /** @var Shopify\Product[] $products */
            $response = $shopify->setShop($this->shopOwner->name)
                ->setAccessToken($this->shopOwner->token)
                ->all( $this->limit, $page);
            $products = [];
            if (is_array($response) && !empty($response)){
                foreach($response as $key => $value){
                    $entity = new Shopify\Product();
                    $products[] = $entity->load($value);
                }
            }
            if (is_array($products) && !empty($products)) {
                foreach ($products as $product) {
                    $eloquent = $product->toEloquent(
                        $this->shopOwner->id
                    );

                    if ($s = $eloquent->save()) {
                        $number = $number + 1;
                        $this->info((string) $eloquent->id . " saved. $number | Page $page");
                    }

                    $variants = $product->getVariants();
                    foreach( $variants as $variant) {
                        $eloquent = $variant->toEloquent(
                            $this->shopOwner->id
                        );

                        $eloquent->save();
                    }

                }
            }

            $page = $page + 1;
        } while(!empty($products));

    }

    private function loadInventory(){

        $total = \App\Model\Variant::count();
        $page = 0;
        \App\Model\Variant::chunk(250, function($variants) use($total, $page){
            $page = $page + 1;
            foreach ($variants as $index => $variant) {
                $shopify = new Shopify\InventoryLevel();
                $shopify->setInventoryItemIds([$variant->inventory_item_id])
                    ->setShop($this->shopOwner->name)
                    ->setAccessToken($this->shopOwner->token);

                /** @var Shopify\InventoryLevel[] $items */
                $items = $shopify->fetch();

                foreach ($items as $item) {
                    $eloquent = $item->toEloquent(
                        $this->shopOwner->id
                    );
                    if ($eloquent->save()) {
                        $this->info(
                            "Inventory " . (string) $eloquent->inventory_item_id
                            . " saved. ($index/250) Page: $page | Total: $total"
                        );
                    }
                }
            }

        });
    }

}