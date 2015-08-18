<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basic extends Model
{
    //
    public $timestamps = false;
    protected $table= 'basics';
    protected $fillable = ['id', 'company', 'phone', 'address'];

    public  function consumeTotal()
    {
        $consumption =  \App\ConsumptionDaily ::  where ('user_id', $this->id)
            -> where('consumable_type', 'App\Network')
            -> select (\DB :: raw('sum( consumption_total) as consume'))
            //-> groupBy('date')
            -> first();
        return $consumption ?  sprintf('%.2f', $consumption->consume /1000): 0 ;    
    }
    public  function costTotal()
    {
        $cost =  \App\ConsumptionDaily ::  where ('user_id', $this->id)
            -> where('consumable_type', 'App\Network')
            -> select (\DB :: raw('sum(cost) as cost'))
            //-> groupBy('date')
            -> first();
        return $cost ?  sprintf('%.2f', $cost->cost /1000): 0 ;    
    }
}
