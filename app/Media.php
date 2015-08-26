<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
require_once '/opt/app/xunsearch/sdk/php/lib/XS.php';
use XS;
use XSSearch;
class Media extends Model
{
    //
    protected $table= 'medias';
    protected $fillable =['name', 'classify_id', 'classify_id_1', 'classify_id_3','user_id', 'comment', 'is_star', 'status'];
    protected $appends = ['statusName'];
    public function consumption_daily()
    {
        return $this->morphMany('App\ConsumptionDaily', 'consumable');
    }
    static function search($request) {
        $__ = array('q', 'm', 'f', 's', 'p', 'ie', 'oe', 'syn', 'xml');
        foreach ($__ as $_) {
            $$_ = $request->input($_, '');
        }
        $f = empty($f) ? '_all' : $f;

        $xs = new XS('media');
        $search = $xs->search;
        $search->setCharset('UTF-8');
        $search->setFuzzy($m === 'yes');

        // synonym search
        $search->setAutoSynonyms($syn === 'yes');

        // set query
        if (!empty($f) && $f != '_all') {
            $search->setQuery($f . ':(' . $q . ')');
                    } else {
                    $search->setQuery($q);
         }
         if ($request->input('device')) {
              $search->setQuery('device_id:'. Device:: getDeviceIdByName($request->input('device')));
         }
         // set sort
         if (($pos = strrpos($s, '_')) !== false) {
            $sf = substr($s, 0, $pos);
            $st = substr($s, $pos + 1);
            $search->setSort($sf, $st === 'ASC');
         }

         // set offset, limit
         $p = max(1, intval($p));
         $n = XSSearch::PAGE_SIZE;
         $search->setLimit($n, ($p - 1) * $n);
         $docs = $search->search();
         $results = [];
         foreach ($docs as $doc) {
            $result = [];
            $result['id']    = $doc->media_id;
            $result['text']  = $doc->name;
            $results[] = $result;
        }
        return $results;

  }
    public function classify(){
        return $this->belongsTo('App\Classification');
    }
    public function classify_1(){
        return $this->belongsTo('App\Classification','classify_id_1', 'id');
    }
    public function classify_3(){
        return $this->belongsTo('App\Classification', 'classify_id_3', 'id');
    }
    public function devices(){
        return $this->belongsToMany('App\Device')->withPivot(['adx','group']);
    }
   public  static function create(array $attributes=[]){
        $media = parent ::create($attributes);
        $media->devices()->attach($attributes['device_media']);
        return $media;
    }
    function update(array $attributes=[]) {
        $update = parent :: update($attributes);
        $this->devices()->detach();
        $this->devices()->attach($attributes['device_media']);
        return $update;
    }
    function getStatusNameAttribute(){
        return  $this->attributes['status'] == 'Y' ? '启用': '禁用';
    }
}
