<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    //
    protected $table    = 'flows';
    protected $fillable = ['max','min', 'name'];
}
