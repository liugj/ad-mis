<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    //
    protected $table='classification';
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
}
