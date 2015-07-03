<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basic extends Model
{
    //
    public $timestamps = false;
    protected $table= 'basics';
    protected $fillable = ['id', 'company', 'phone', 'address'];
}
