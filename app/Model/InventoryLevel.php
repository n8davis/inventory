<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/19/19
 * Time: 11:51 AM
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class InventoryLevel
 * @package App\Model
 * @property integer $id
 * @property integer $location_id
 * @property integer $available
 * @property string $meta
 * @property string $other_meta
 * @property string $created_at
 * @property string $updated_at
 * @property string $sku
 * @property integer $shop_owner_id
 * @property integer $status
 * @property integer $inventory_item_id
 * @property BelongsTo|ShopOwner $shopOwner
 * @property \Illuminate\Database\Eloquent\Relations\HasOne|InventoryItem $inventoryItem
 */
class InventoryLevel extends AbstractModel
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|InventoryItem
     */
    public function inventoryItem()
    {
        return $this->hasOne("App\Model\InventoryItem");
    }

    public function shopOwner()
    {
        return $this->belongsTo('App\Model\ShopOwner');
    }

    public function toShopify()
    {
        $shopify = new \App\Manager\Shopify\InventoryLevel();

        $locationId = null;
        $selectedLocation = Location::where([
            ['shop_owner_id', '=', $this->shopOwner->id],
            ['selected', '=', 1]
        ])->first();

        if (isset($selectedLocation)) {
            $locationId = $selectedLocation->id;
        } else {
            return $shopify;
        }
        
        $shopify->setAvailable($this->available)
            ->setLocationId($locationId)
            ->setInventoryItemId($this->inventory_item_id)
            ->setRelocateIfNecessary(true)
            ->setDisconnectIfNecessary(true)
            ->setShop($this->shopOwner->name)
            ->setAccessToken($this->shopOwner->token);
        return $shopify;
    }
}