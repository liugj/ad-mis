<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    //
    protected $table='devices';
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
    public static function getDeviceIdByName($name_en) {
       $device = self  :: where('name_en', $name_en)->first();
       return $device->id;
       
    }
}
