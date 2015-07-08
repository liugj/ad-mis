<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    //
    protected $tables='recharges';
    
     protected $fillable = ['user_id', 'money', 'administrator_id'];
}
