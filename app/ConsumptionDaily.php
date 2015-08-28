<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsumptionDaily extends Model
{
    //
    protected $fillable= ['idea_id', 'plan_id', 'user_id', 'datetime', 'date', 'consumable_type', 
              'consumable_id', 'consumption_total', 'click_total', 'install_total', 
              'open_total', 'download_total', 'exhibition_total', 'parent_id', 'cost'
                  ];
    protected $table = 'consumptions_daily';
    static $Types = [
        "App\Device"        => '设备类型',
        "App\Region"        => '地域',
        "App\Classification"=> '媒体类型',
        "App\Operator"      => '运营商',
        "App\NetWork"       => '网络类型',
        "App\Media"         => '媒体',
        "App\Manufacturer"   => '手机制造商',
        ];
    public function idea(){
        return $this->belongsTo('App\Idea');
    }
    public function consumable()
    {
        return $this->morphTo();
    }
}
