<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    //
   protected $table='regions';
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
}
