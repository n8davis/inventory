<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/19/19
 * Time: 11:52 AM
 */

namespace App\Model;

use Illuminate\Database\QueryException;

/**
 * Class ShopOwner
 * @package App\Model
 * @property integer $id
 * @property integer $location_id
 * @property string $name
 * @property string $email
 * @property string $timezone
 * @property string $meta
 * @property string $created_at
 * @property string $updated_at
 * @property string $token
 * @property integer $status
 * @property Configuration $configurations
 * @property Cron $crons
 * @property InventoryItem $inventoryItems
 * @property InventoryLevel $inventoryLevels
 * @property Location $locations
 * @property Product|array $products
 * @property Variant $variants
 */
class ShopOwner extends AbstractModel
{
    public $incrementing = false;
    
    public function delete()
    {
        try {
            $this->selectedConnections()->delete();
            $this->configurations()->delete();
            $this->crons()->delete();
            $this->inventoryItems()->delete();
            $this->inventoryLevels()->delete();
            $this->locations()->delete();
            $this->products()->delete();
            $this->variants()->delete();
            return parent::delete();
        }
        catch (QueryException $queryException){

        }
        catch (\Exception $exception){

        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Configuration[]
     */
    public function configurations(){
        return $this->hasMany("App\Model\Configuration");
    }

    /**
     * Timezone set by user in General Settings
     * @return null
     */
    public function timezone()
    {
        foreach( $this->configurations as $config ) {
            if( $config->entity === 'timezone'
                && strlen($config->value) > 0
            ) {
                return $config->value;
            }
        }
        return $this->timezone;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Cron[]
     */
    public function crons(){
        return $this->hasMany("App\Model\Cron");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|InventoryItem[]
     */
    public function inventoryItems(){
        return $this->hasMany("App\Model\InventoryItem");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|InventoryLevel[]
     */
    public function inventoryLevels(){
        return $this->hasMany("App\Model\InventoryLevel");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Location[]
     */
    public function locations(){
        return $this->hasMany("App\Model\Location");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Product[]
     */
    public function products(){
        return $this->hasMany("App\Model\Product");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Variant[]
     */
    public function variants(){
        return $this->hasMany("App\Model\Variant");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|ShopOwnerConnection[]
     */
    public function selectedConnections(){
        return $this->hasMany("App\Model\ShopOwnerConnection");
    }

}