<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    //
    protected $table = 'consumptions';
    public function  plan(){
        return $this->belongsTo('App\Plan');
    }
    public function  idea(){
        return $this->belongsTo('App\Idea');
    }
}
