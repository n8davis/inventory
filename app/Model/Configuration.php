<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

/**
 * Class Configuration
 * @package App\Model
 * @property string $entity
 * @property string $value
 * @property string $shop_owner_id
 */
class Configuration extends Model
{
    protected $appends = ['name', 'type', 'is_dropdown', 'dropdown_settings'];

    private $initialConfiguration = [
        'timezone',
        'processing',
        'emails',
        'run_inventory',
        'test_mode'
    ];

    private $dropdowns = ['timezone', 'processing', 'run_inventory', 'test_mode'];

    public function getIsDropDownAttribute()
    {
        if (in_array($this->entity, $this->dropdowns)) {
            return true;
        }
        return false;
    }

    public function getDropDownSettingsAttribute()
    {
        $settings = [];
        if (in_array($this->entity, $this->dropdowns)) {

            switch ($this->entity){
                case 'timezone':
                    $settings = [
                        'America/New_York' => 'Eastern',
                        'America/Chicago' => 'Central',
                        'America/Denver' => 'Mountain',
                        'America/Los_Angeles' => 'Pacific',
                    ];
                    break;
                case 'processing':
                case 'test_mode':
                    $settings = [
                        0 => 'No',
                        1 => 'Yes',
                    ];
                    break;
                case 'run_inventory':
                    $settings = [
                        '00:00' => '12:00 AM',
                        '01:00' => '01:00 AM',
                        '02:00' => '02:00 AM',
                        '03:00' => '03:00 AM',
                        '04:00' => '04:00 AM',
                        '05:00' => '05:00 AM',
                        '06:00' => '06:00 AM',
                        '07:00' => '07:00 AM',
                        '08:00' => '08:00 AM',
                        '09:00' => '09:00 AM',
                        '10:00' => '10:00 AM',
                        '11:00' => '11:00 AM',
                        '12:00' => '12:00 PM',
                        '13:00' => '01:00 PM',
                        '14:00' => '02:00 PM',
                        '15:00' => '03:00 PM',
                        '16:00' => '04:00 PM',
                        '17:00' => '05:00 PM',
                        '18:00' => '06:00 PM',
                        '19:00' => '07:00 PM',
                        '20:00' => '08:00 PM',
                        '21:00' => '09:00 PM',
                        '22:00' => '10:00 PM',
                        '23:00' => '11:00 PM',
                    ];
                    break;
            }
        }
        return $settings;

    }

    public function shopOwner()
    {
        return $this->belongsTo('App\Model\ShopOwner' );
    }

    public function getNameAttribute()
    {
        $splitName = explode( ' ' , trim( $this->entity ) );
        return array_key_exists(1, $splitName)
            ? $splitName[1]
            : null;
    }

    public function getTypeAttribute()
    {
        $splitName = explode( ' ' , trim( $this->entity ) );
        return array_key_exists(0, $splitName)
            ? $splitName[0]
            : null;
    }

    public static function init($shopOwner)
    {
        $date           = new \DateTime('', new \DateTimeZone('UTC'));
        $configurations = [];
        $entity         = new self;
        foreach ($entity->initialConfiguration as $config) {
            $configuration = [
                'entity' => $config,
                'shop_owner_id' => $shopOwner->id,
                'created_at' => $date->format('Y-m-d H:i:s'),
                'updated_at' => $date->format('Y-m-d H:i:s'),
                'value' => ''
            ];
            switch ($config) {
                case 'emails':
                    $configuration['value'] = $shopOwner->email;
                    break;
                case 'timezone':
                    $configuration['value'] = $shopOwner->timezone;
                    break;
            }

            $configurations[] = $configuration;
        }

        try {
            return self::insert($configurations);
        } catch (QueryException $queryException){
            return false;
        } catch (\Exception $exception){
            return false;
        }
    }

}