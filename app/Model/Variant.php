<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/19/19
 * Time: 11:52 AM
 */

namespace App\Model;

/**
 * Class Variant
 * @package App\Model
 * @property integer $id
 * @property string $title
 * @property string $sku
 * @property string $meta
 * @property integer $inventory_item_id
 * @property integer $product_id
 * @property string $other_meta
 * @property string $created_at
 * @property string $updated_at
 * @property integer $shop_owner_id
 * @property integer $status
 * @property InventoryLevel $inventoryLevel
 */
class Variant extends AbstractModel
{

    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|InventoryLevel
     */
    public function inventoryLevel()
    {
        return $this->hasOne(
            "App\Model\InventoryLevel",
            "inventory_item_id",
            "inventory_item_id"
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Product
     */
    public function product()
    {
        return $this->belongsTo("App\Model\Product");
    }

    public function toQuickbooks()
    {

    }
}