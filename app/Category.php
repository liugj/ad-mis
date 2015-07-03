<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
   protected $table='categories';
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
}
