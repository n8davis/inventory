<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/19/19
 * Time: 11:51 AM
 */

namespace App\Model;

/**
 * Class Location
 * @package App\Model
 * @property integer $id
 * @property string $meta
 * @property string $created_at
 * @property string $updated_at
 * @property integer $shop_owner_id
 * @property integer $status
 * @property \Illuminate\Database\Eloquent\Relations\HasMany|InventoryItem $inventoryItems
 */
class Location extends AbstractModel
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|InventoryItem
     */
    public function inventoryItems()
    {
        return $this->hasMany(
            "App\Model\InventoryItem"
        );
    }

}