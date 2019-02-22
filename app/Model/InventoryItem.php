<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/19/19
 * Time: 11:51 AM
 */

namespace App\Model;

/**
 * Class InventoryItem
 * @package App\Model
 * @property integer $id
 * @property integer $location_id
 * @property string $sku
 * @property string $meta
 * @property string $other_meta
 * @property string $created_at
 * @property string $updated_at
 * @property integer $shop_owner_id
 * @property integer $status
 * @property \Illuminate\Database\Eloquent\Relations\BelongsTo|Variant $variant
 * @property \Illuminate\Database\Eloquent\Relations\HasOne|InventoryLevel $inventoryLevel
 */
class InventoryItem extends AbstractModel
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|InventoryLevel
     */
    public function inventoryLevel()
    {
        return $this->hasOne("App\Model\InventoryLevel");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Variant
     */
    public function variant()
    {
        return $this->belongsTo("App\Model\Variant");
    }
}