<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
	//
	protected $fillable   = ['name','adx'];
	protected $table = 'industries';
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
}
