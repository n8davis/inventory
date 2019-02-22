<?php
namespace App\Model;


/**
 * Class Cron
 * @package App\Model
 * @method find( $id )
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $shop_owner_id
 * @property string $status
 * @property string $name
 * @property string $shop
 * @property integer $pid
 * @property integer $type
 * @property ShopOwner $shopOwner
 */
class Cron extends AbstractModel
{


    const TYPE_WEBHOOK             = 1;
    const TYPE_FULFILLMENT_SERVICE = 2;
    const TYPE_SHOP                = 3;
    const TYPE_DEFAULT             = 4;

    const QUEUED     = 1;
    const PROCESSING = 2;
    const COMPLETE   = 3;
    const ERROR      = 4;
    const KILLED     = 5;

    protected $fillable = [ 'status' ];

    protected $hidden = [ 'pid' ];

    protected $appends = [ 'date' ];

    public function shopOwner()
    {
        return $this->belongsTo('App\Model\ShopOwner' );
    }

    public function getDateAttribute()
    {
        $timezone = $this->shopOwner->timezone;
        $date     = new \DateTime(
            $this->created_at->tz($timezone),
            new \DateTimeZone($timezone)
        );
        return $date->format('M d, Y h:i A');
    }

    public function getStatusAttribute($value)
    {
        $status = "N/A";
        switch ( $value ){
            case self::QUEUED:
                $status  = 'Queued';
                break;
            case self::PROCESSING:
                $status  = 'Processing';
                break;
            case self::COMPLETE:
                $status  = 'Complete';
                break;
            case self::ERROR:
                $status  = 'Error';
                break;
        }
        return $status;
    }

    public function getNameAttribute($value)
    {

        $name = ltrim( $value, "App\Console\Commands\\" ) ;
        $name = str_replace( "\\" , " " , $name );

        return $name;

    }

    public function changeStatus( $status )
    {
        $this->status = $status;
        return $this->save();
    }

    /**
     * Initialize Initial Cron Processes on install
     *
     * @param $shopOwner
     */
    public static function init($shopOwner)
    {

        $processes = [ self::TYPE_WEBHOOK , self::TYPE_FULFILLMENT_SERVICE , self::TYPE_SHOP ];
        $crons     = [];
        foreach( $processes as $process ) {

            $cron                    = [];
            $cron[ 'status' ]        = self::QUEUED;
            $cron[ 'type' ]          = $process;
            $cron[ 'shop' ]          = $shopOwner->name;
            $cron[ 'shop_owner_id' ] = $shopOwner->id;
            $crons[]                 = $cron;

        }

        self::insert( $crons );
    }
}