<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //
    protected $table ='plans';
    protected $fillable = ['name', 'budget', 'user_id'];
    protected $appends = ['state'];


    public function  ideas() 
    {
        return $this->hasMany('App\Idea');
    }
    public  function consumeTotalByDate($date)
    {
        $consumption =  \App\Consumption ::  where ('plan_id', $this->id)
            -> where('date', $date)
            -> select (\DB :: raw('sum( price ) as consume'))
            //-> groupBy('date')
            -> first();
        return $consumption ?  $consumption->consume : 0 ;    
    }
    public function getStateAttribute()
    {
        return $this->attributes['status'] == 1 ? '停止' : '启用';
    }

}
