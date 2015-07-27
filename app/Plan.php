<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //
    protected $table ='plans';
    protected $fillable = ['name', 'budget', 'user_id', 'start_time', 'end_time'];
    protected $appends = ['state'];


    public function  ideas() 
    {
        return $this->hasMany('App\Idea');
    }
    public  function consumeTotalByDate($date)
    {
        $consumption =  \App\ConsumptionDaily ::  where ('plan_id', $this->id)
            -> where('date', $date)
            -> select (\DB :: raw('sum( consumption_total ) as consume'))
            //-> groupBy('date')
            -> first();
        return $consumption ?  $consumption->consume /1000: 0 ;    
    }
    public function getStateAttribute()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1 ? '停止' : '启用';
    }

}
