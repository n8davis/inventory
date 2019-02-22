<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/21/19
 * Time: 10:53 AM
 */

namespace App\Model;

/**
 * Class ShopOwnerConnection
 * @package App\Model
 * @property int $connection_id
 * @property int $shop_owner_id
 */
class ShopOwnerConnection extends AbstractModel
{

    protected $hidden = ['client_id', 'client_secret'];

    protected $appends = ['has_client_id', 'has_client_secret'];

    public function getHasClientIdAttribute()
    {
        return $this->client_id ?? false;
    }

    public function getHasClientSecretAttribute()
    {
        return $this->client_secret ?? false;
    }

    public function shopOwner()
    {
        return $this->belongsTo("App\Model\ShopOwner");
    }
    public function connection()
    {
        return $this->belongsTo("App\Model\Connection");
    }
}