<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsumptionDaily extends Model
{
    //
    protected $fillable= ['idea_id', 'plan_id', 'user_id', 'datetime', 'date', 'consumable_type', 'consumable_id', 'consumption_total', 
                        'click_total', 'install_total', 'open_total', 'download_total', 'exhibition_total', 'parent_id'];
    protected $table = 'consumptions_daily';
    public function idea(){
        return $this->belongsTo('App\Idea');
    }
    public function consumable()
    {
        return $this->morphTo();
    }
}
