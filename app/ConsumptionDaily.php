<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsumptionDaily extends Model
{
    //
    protected $table = 'consumptions_daily';
    public function idea(){
        return $this->belongsTo('App\Idea');
    }
    public function consumable()
    {
        return $this->morphTo();
    }
}
