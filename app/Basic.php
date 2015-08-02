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
        return $consumption ?  $consumption->consume /1000: 0 ;    
    }
}
