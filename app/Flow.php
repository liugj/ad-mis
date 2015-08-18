<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    //
    protected $table    = 'flows';
    protected $fillable = ['max','min', 'name'];
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
}
