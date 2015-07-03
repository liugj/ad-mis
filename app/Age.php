<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Age extends Model
{
    //
    protected $table='ages';
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
}
