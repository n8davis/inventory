<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 10/4/18
 * Time: 12:26 PM
 */

namespace App\Model;


use App\Manager\Basic\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractModel
 * @package App\Model
 * @property int $shop_owner_id
 * @property string $status
 * @property string $shop
 */
class AbstractModel extends Model
{
    /** @var array $fillable */
    protected $fillable = [ 'status' ];

    /** @var array $appends */
    protected $appends = ['status_title', 'has_error'];

    /**
     * @return bool|string
     */
    public function getStatusTitleAttribute()
    {
        return Status::get($this->status);
    }

    /**
     * @return string
     */
    public function getHasErrorAttribute()
    {
        return $this->status === Status::ERROR ? 'Yes' : 'No';
    }

}