<?php

namespace App\Console\Commands\Shop;


use App\Console\Commands\AbstractCommand;
use App\Manager\Basic\Status;
use App\Manager\Shopify\InventoryLevel;
use App\Model\Location;
use App\Model\ShopOwner;
use App\Model\Variant;

class InventoryConnect extends AbstractCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:inventory_connect {shopOwnerId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connects Shopify Inventory To Selected Location';


    /**
     * InventoryConnect constructor.
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
        $this->setup();

        $selectedLocation = Location::where([
            ['shop_owner_id', '=', $this->shopOwner->id],
            ['selected', '=', 1]
        ])->first();

        if (isset($selectedLocation)) {
            $selectedLocationId = $selectedLocation->id;
        }

        if (!isset($selectedLocationId)) {
            return null;
        }

        Variant::where([
            ['status', '!=', Status::CONNECTED]
        ])->chunk(250, function($variants) use($selectedLocationId) {
            /** @var Variant $variant */
            foreach ($variants as $variant) {
                if ($variant->inventory_item_id === null) {
                    $variant->status = Status::DISCONNECTED;
                    $variant->save();
                    continue;
                }
                if (strlen($variant->sku) === 0) {
                    $variant->status = Status::DISCONNECTED;
                    $variant->save();
                    continue;
                }
                $shopify = new InventoryLevel();
                $shopify->setShop($this->shopOwner->name)
                    ->setAccessToken($this->shopOwner->token)
                    ->setInventoryItemId($variant->inventory_item_id)
                    ->setLocationId($selectedLocationId)
                    ->setDisconnectIfNecessary(true)
                    ->setRelocateIfNecessary(true)
                    ->connect();

                $this->info( "Http $shopify->httpCode $variant->id");

                switch ($shopify->httpCode){
                    case 200:case 201:
                        $variant->status = Status::CONNECTED;
                        break;
                    default:
                        $variant->status = Status::DISCONNECTED;
                        $this->info(json_encode($shopify->getResults()));
                        break;
                }

                $variant->save();

            }
        });
        return null;
    }
}