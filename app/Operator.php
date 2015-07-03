<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    //
    protected $table='operators';
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
}
