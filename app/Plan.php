<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	//
	protected $table ='plans';
    protected $fillable = ['name', 'budget', 'user_id'];

	public function  ideas() {
		return $this->hasMany('App\Idea');
	}
}
