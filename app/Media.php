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
         if ($request->input('device_id')) {
              $search->setQuery('device_id:'. $request->input('device_id'));
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
            $result['id']    = $doc->id;
            $result['text']  = $doc->name;
            $results[] = $result;
        }
        return $results;

  }
}
