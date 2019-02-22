<?php
/**
 * Created by PhpStorm.
 * User: nate
 * Date: 2/21/19
 * Time: 10:12 AM
 */

namespace App\Model;


class Connection extends AbstractModel
{

    protected $appends = ['is_selected'];

    public function getIsSelectedAttribute()
    {
        $selected = $this->selectedConnection;
        unset($this->selectedConnection);
        if (!empty($selected->toArray())) {
            return true;
        }

        return false;
    }

    public function shopOwner()
    {
        return $this->belongsTo("App\Model\ShopOwner");
    }

    public function selectedConnection()
    {
        return $this->hasMany("App\Model\ShopOwnerConnection");
    }
}