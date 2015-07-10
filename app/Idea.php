<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    //
    protected $table      = 'ideas';
    protected $primaryKey = 'id';
    protected $fillable   = ['name','user_id', 'plan_id','type','bid','frequency','budget','size_id','status', 'link','link_text',
                            'display_type','alt','src','click_action_id','gender','pay_type', 'timerange','start_time','end_time'
                            ];
    protected $appends = ['state'];                        
    static  $arrStatus = [
        1=>'待审核',
        0=>'审核通过',
        2=>'审核未通过',
        3=>'投放中',
        4=>'停止投放'
    ];

    public function update(array $attributes=[]) {
        list($attributes['start_time'], $attributes['end_time']) = explode('至', $attributes['daterange']);
        $attributes['end_time']  = sprintf('%s 23:59:59', trim($attributes['end_time']));
       $update =  parent :: update($attributes);

        $this->industries()->detach();
        if (isset($attributes['industry'])) {
            $this->industries()->attach($attributes['industry']);
        }
        $this->os()->detach();
        if (isset($attributes['os'])) {
            $this->os()->attach($attributes['os']);
        }
        $this->network()->detach();
        if (isset($attributes['network'])) {
            $this->network()->attach($attributes['network']);
        }
        $this->operators()->detach();
        if (isset($attributes['operator'])) {
            $this->operators()->attach($attributes['operator']);
        }
        $this->age()->detach();
        if (isset($attributes['age'])) {
            $this->age()->attach($attributes['age']);
        }
        $this->categories()->detach();
        if (isset($attributes['category'])) {
            $this->categories()->attach($attributes['category']);
        }
        $this->ban()->detach();
        if (isset($attributes['ban'])) {
            $this->ban()->attach($attributes['ban']);
        }
        $this->devices()->detach();
        if (isset($attributes['device'])) {
            $this->devices()->attach($attributes['device']);
        }
        $this->regions()->detach();
        if (isset($attributes['region'])) {
            $this->regions()->attach($attributes['region']);
        }
        $this->classification()->detach();
        if (isset($attributes['classify'])) {
            $this->classification()->attach($attributes['classify']);
        }
        return $update;
    }
    public static function create(array $attributes=[]) {
        list($attributes['start_time'], $attributes['end_time']) = explode('至', $attributes['daterange']);
        $attributes['end_time']  = sprintf('%s 23:59:59', trim($attributes['end_time']));
        $idea = parent :: create($attributes);
        if (isset($attributes['industry'])) {
            $idea->industries()->attach($attributes['industry']);
        }
        if (isset($attributes['os'])) {
            $idea->os()->attach($attributes['os']);
        }
        if (isset($attributes['network'])) {
            $idea->network()->attach($attributes['network']);
        }
        if (isset($attributes['operator'])) {
            $idea->operators()->attach($attributes['operator']);
        }
        if (isset($attributes['age'])) {
            $idea->age()->attach($attributes['age']);
        }
        if (isset($attributes['category'])) {
            $idea->categories()->attach($attributes['category']);
        }
        if (isset($attributes['ban'])) {
            $idea->ban()->attach($attributes['ban']);
        }
        if (isset($attributes['device'])) {
            $idea->devices()->attach($attributes['device']);
        }
        if (isset($attributes['region'])) {
            $idea->regions()->attach($attributes['region']);
        }
        if (isset($attributes['classify'])) {
            $idea->classification()->attach($attributes['classify']);
        }
        return $idea;
    }
    public function age(){
        return $this->belongsToMany('App\Age');
    }

    public function industries() {
        return $this->belongsToMany('App\Industry');
    }
    public function plan(){
        return $this->belongsTo('App\Plan');
    }
    public function classification() {
        return $this->belongsToMany('App\Classification', 'classify_idea', 'idea_id', 'classify_id');
    }
    public function ban() {
        return $this->belongsToMany('App\Classification', 'ban_idea', 'idea_id', 'ban_id');
    }
    public function categories() {
        return $this->belongsToMany('App\Category');
    }
    public function os() {
        return $this->belongsToMany('App\Os');
    }
    public function network() {
        return $this->belongsToMany('App\Network');
    }
    public function regions() {
        return $this->belongsToMany('App\Region');
    }
    public function operators() {
        return $this->belongsToMany('App\Operator');
    }
    public function devices() {
        return $this->belongsToMany('App\Device');
    }
    public function timezone() {
        return $this->belongsToMany('App\Timezone');
    }
    public function  click_action(){
        return $this->belongsTo('App\ClickAction');
    }
    public function  size(){
        return $this->belongsTo('App\Size');
    }
    public  function consumeTotalByDate($date)
    {
        $consumption =  \App\Consumption ::  where ('idea_id', $this->id)
            -> where('date', $date)
            -> select (\DB :: raw('sum( price ) as consume'))
            //-> groupBy('date')
            -> first();
        return $consumption ?  $consumption->consume : 0 ;    
    }
    public function getStateAttribute()
    {
        return self :: $arrStatus[$this->attributes['status']];
    }
}
