<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/19/19
 * Time: 11:52 AM
 */

namespace App\Model;

/**
 * Class Product
 * @package App\Model
 * @property integer $id
 * @property string $title
 * @property string $meta
 * @property string $other_meta
 * @property string $created_at
 * @property string $updated_at
 * @property integer $shop_owner_id
 * @property integer $status
 * @property \Illuminate\Database\Eloquent\Relations\HasMany|Variant[] $variants
 */
class Product extends AbstractModel
{

    public $incrementing = false;

    protected $appends = ['image', 'type', 'vendor',
        'total_in_stock', 'total_variants'];

    public function getTotalVariantsAttribute(){
        return count($this->variants);
    }

    public function getTotalInStockAttribute()
    {
        $total = null;

        $variants = $this->variants;
        if (isset($variants)) {
            foreach ($variants as $variant) {
                $total += $variant->inventoryLevel->available;
            }
        }

        return $total;
    }

    public function getImageAttribute()
    {
        $object = json_decode($this->meta);
        if (is_object($object) && property_exists($object, 'images')) {
            if (isset($object->images[0])) {
                if (is_object($object->images[0]) && property_exists($object->images[0], 'src')) {
                    return $object->images[0]->src;
                }
            }
        }
        return null;
    }

    public function getTypeAttribute()
    {
        $object = json_decode($this->meta);
        if (is_object($object) && property_exists($object, 'product_type')) {
            return $object->product_type;
        }
        return null;
    }

    public function getVendorAttribute()
    {
        $object = json_decode($this->meta);
        if (is_object($object) && property_exists($object, 'vendor')) {
            return $object->vendor;
        }
        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Variant[]
     */
    public function variants()
    {
        return $this->hasMany("App\Model\Variant");
    }

}