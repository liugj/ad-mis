<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Os extends Model
{
    //
   protected $table='os';
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
}
