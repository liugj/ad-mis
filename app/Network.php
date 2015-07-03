<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    //
    protected $table='network';
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
}
