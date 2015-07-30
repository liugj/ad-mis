<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\ConsumptionDaily;
use App\Idea;
use App\Basic;
use App\Plan;
use DB;
use Log;
class ConsumptionHourly extends Command implements SelfHandling
{
    /**
     * signature 
     * 
     * @var string
     * @access protected
     */
    protected $signature = 'consumption_hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumption Hourly';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }
    /**
     * _charge 
     * 
     * @param mixed $dateHour 
     * @access protected
     * @return mixed
     */
    protected  function _charge($dateHour) 
    {
        $results = array();
        $chargeFile = sprintf('/opt/log/charge/charge_python.%s.log', $dateHour);
        if (file_exists($chargeFile)) {
            $fp = fopen($chargeFile , 'r');
            if ($fp) {
                while(!feof($fp)) {
                    $line  =  fgets($fp, 8192);
                    if (!$line) break;
                    if ((($pos = strpos($line, 'CHARGE:'))!== false) && $s = substr($line, $pos+7)){
                        $s = trim($s);
                        $matches = json_decode($s, TRUE);
                        $results [$matches['impid']] = $matches['price'];
                    }  
                }
            }
        }

        return $results;
    }
    protected function _charge2($idea_id, $sessionId) {
        $charge =  DB::table('charge_record')->select('price', 'cost')
            -> where ('idea_id', $idea_id) 
            -> where ('session_id', $sessionId) 
            ->first();
        return $charge ? [$charge->price*1000, $charge->cost*1000] : [0.0,0.0];
    }
    /**
     * _stat 
     * 
     * @param mixed $hour 
     * @access protected
     * @return mixed
     */
    protected function _stat($hour)
    {
        $p = '#^(?P<ip>\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3})\s-\s(.*)\s\[(?P<datetime>.*)\]\s\"(?P<request>.*?)"\s(\d{3,})\s(\d+)\s\"([^\s]*)\"\s\"(.*?)\"\s\"(.*)\"$#ui' ;
# $accessLogs = glob(sprintf('/opt/log/nginx/%s*_monitor.%sshoozen.net.access.log', $hour, env('APP_ENV') == 'prod'? '':   env('APP_ENV') . '.'));
        $accessLogs = glob(sprintf('/opt/log/nginx/%s*_monitor.shoozen.net.access.log', $hour));
        $items =  [];
        foreach ($accessLogs as $accessLog) {
            $fp = fopen($accessLog, 'r');
            while(!feof($fp)) {
                $line  =  fgets($fp, 8192);
                if (!$line) break;
                if(preg_match($p, $line, $matches)){
                    if (preg_match('#GET /track.gif\?(.*?)HTTP#i', $matches[4], $matches1)){
                        $item = array();
                        foreach (explode('$$$', $matches1[1]) as $s){
                            if (strpos($s, '*') === false||$s[strlen($s)-1] == '*' ) continue;
                            list($key, $value) = explode('*', $s);
                            $item[$key] = trim($value);
                        }
                        if (!isset($item['id']) || !isset($item['type'])) continue;
                        $id = $item['id'];
                        if (isset($items[$id])) {
                            if ($item['type'] =='win_notice'){
                                //$items[$id]['cost'] = $this->_cost($item['win_price']); //解析win_notice
                            }
                            else $items[$id][$item['type']] = 1;
                            foreach (['region', 'classification', 'operator', 'device_type', 'network'] as $key) {
                                if (isset($item[$key]) && !isset($items[$id][$key]))  $items[$id][$key] = $item[$key] ;
                            }
                        }else{
                            unset($item['id']);
                            $items [$id] = $item;
                            if ($item['type'] =='win_notice'){
                                //$item[$id]['cost'] = 0.0;//解析win_notice
                               // $items[$id]['cost'] = $this->_cost($item['win_price']); //解析win_notice
                            }
                            else $items[$id][$item['type']] = 1;
                        }
                    }
                }
            }
            fclose($fp);
        }
        if (1) {
            foreach ($items as $sessionId => &$item) {
if (!isset($item['idea_id'])) continue;
               list($item['price'], $item['cost']) = $this->_charge2($item['idea_id'], $sessionId);
            //   list($item['price'], $item['cost1']) = $this->_charge2($item['idea_id'], $sessionId);
            }
        }else{
            $charges = $this->_charge($hour);
            foreach ($charges as $sessionId =>$price) {
                $items [$sessionId]['price'] = $price;
            }
        }
        $stats = [];
        foreach ($items  as $sessionId=>$item) {
            if (!isset($item['idea_id'])) continue;
            foreach (['App\Region'=>'region', 'App\Classification'=>'classification', 'App\Operator'=>'operator', 'App\Device'=>'device_type', 'App\Network'=>'network'] as $consumable_type=>$consumable_key) {
                $consumable_value = isset($item[$consumable_key]) && $item[$consumable_key]!= 'None' ? $item[$consumable_key] : '0' ;
                if ($consumable_key == 'region') {
                    if (strpos($consumable_value, ',') == false) {
                        $consumable_id = $consumable_value;
                        $parent_id     = 0;
                    }else{
                        list($parent_id, $consumable_id) = explode(',', $consumable_value);
                    }
                    $stat_key = sprintf('%d_%s_%d_%d', $item['idea_id'], $consumable_type, $parent_id, $consumable_id); 
                }elseif ($consumable_key== 'classification') {
                    $stat_key = sprintf('%d_%s_%d', $item['idea_id'], $consumable_type, $consumable_value); 

                }else{
                    $stat_key = sprintf('%d_%s_%s', $item['idea_id'], $consumable_type, $consumable_value); 
                }
                if (isset($stats[$stat_key])) {
                    foreach (['show'=>'exhibition_total', 'click'=>'click_total', 'download'=>'download_total', 'open'=>'open_total', 
                            'install'=>'install_total', 'price'=>'consumption_total', 'cost'=>'cost'] as $key=>$value) {
                        if (isset($item[$key]) && ($key == 'price' || $key=='cost')) {
                            $stats[$stat_key][$value] += $item[$key];
                        }elseif (isset($item[$key])){
                            $stats[$stat_key][$value] += 1;
                        }
                    }
                }else{
                    $stats[$stat_key] = ['idea_id' => $item['idea_id'], 'consumable_id'=> $consumable_value, 'consumable_type'=>$consumable_type ];
                    if ($consumable_key == 'region') {
                        $stats[$stat_key]['consumable_id'] = $consumable_id;
                        $stats[$stat_key]['parent_id']    = $parent_id;
                    }else{
                        $stats[$stat_key]['consumable_id'] = $consumable_value;
                    }

                    foreach (['show'=>'exhibition_total', 'click'=>'click_total', 'download'=>'download_total','open'=>'open_total', 
                            'cost'=>'cost', 'install'=>'install_total', 'price'=>'consumption_total'] as $key=>$value) {
                        if (isset($item[$key]) && ($key == 'price' || $key=='cost')) {
                            $stats[$stat_key][$value] = $item[$key];
                        }elseif (isset($item[$key])){
                            $stats[$stat_key][$value] = 1;
                        }else{
                            $stats[$stat_key][$value] = 0;
                        }
                    }
                }
            }
        }
        return $stats;
    }
    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $time        =      time();
        for ($i=1; $i<2; $i++) {
            $dateTime    = date('Y-m-d H',  $time - 3600 * $i);
            $date        = date('Y-m-d',  $time - 3600 *$i);
            $lastDateHour = date('YmdH', $time - 3600 *$i);
            $lastDate = date('Ymd', $time - 3600 *$i);
#$lastDateHour = date('Ym2510', $time - 3600 *$i);
            $stats        = $this->_stat($lastDateHour);

            $ideas  = [];
            foreach (array_unique(array_column($stats, 'idea_id')) as $id) {
                $c   = Idea ::where('id', $id)->select(['id', 'user_id', 'plan_id', 'status'])->first();
                if ($c) {
                    $ideas[$id]= $c->toArray();
                }
            }
            $ideas_stats     = [];
            $users_stats     = [];
            $plans_stats     = [];
            foreach ($stats  as $stat) {
                if (!isset($ideas[$stat['idea_id']])) continue;
                $consumption =  collect($stat+$ideas[$stat['idea_id']]);
                $consumption_daily =  ConsumptionDaily :: where('idea_id'        ,  $consumption->get('idea_id'))
                    -> where('consumable_type',  $consumption->get('consumable_type')) 
                    -> where('parent_id'  ,  $consumption->get('parent_id', 0))
                    -> where('consumable_id'  ,  $consumption->get('consumable_id'))
                    -> where('datetime'       ,  $dateTime)
                    -> first(); 
                if ($consumption->get('consumable_type') =='App\Network') {
                    $idea_id = $stat['idea_id'];  
                    if (!isset($ideas_stats[$idea_id])) {
                        $ideas_stats[$idea_id] = ['user_id' => $consumption->get('user_id'), 'plan_id'=> $consumption->get('plan_id'),
                            'cost'=>  $consumption->get('cost'), 'consumption_total'=> $consumption->get('consumption_total')];    
                    }else{
                        $ideas_stats[$stat['idea_id']]['cost'] += $consumption->get('cost');
                        $ideas_stats[$stat['idea_id']]['consumption_total'] += $consumption->get('consumption_total');

                    }
                    if ($consumption_daily) {
                        $ideas_stats[$stat['idea_id']]['cost'] -= $consumption_daily->cost;
                        $ideas_stats[$stat['idea_id']]['consumption_total'] -= $consumption_daily->consumption_total;
                    }
                }
                if ($consumption_daily) {
                    $consumption_daily->delete(); 
                }
                ConsumptionDaily ::create($consumption->all() + array('datetime'=>$dateTime, 'date'=>$date));
            }
            foreach($ideas_stats as $idea_stat) {
                $plan_id = $idea_stat['plan_id'];
                $user_id = $idea_stat['user_id'];
                if (!isset($plans_stats[$plan_id])) {
                    $plans_stats[$plan_id]['cost'] =  $idea_stat['cost'];
                    $plans_stats[$plan_id]['consumption_total'] =  $idea_stat['consumption_total'];
                }else{
                    $plans_stats[$plan_id]['cost'] +=  $idea_stat['cost'];
                    $plans_stats[$plan_id]['consumption_total'] +=  $idea_stat['consumption_total'];
                }

                if (!isset($users_stats[$user_id])) {
                    $users_stats[$user_id]['cost'] =  $idea_stat['cost'];
                    $users_stats[$user_id]['consumption_total'] =  $idea_stat['consumption_total'];
                }else{
                    $users_stats[$user_id]['cost'] +=  $idea_stat['cost'];
                    $users_stats[$user_id]['consumption_total'] +=  $idea_stat['consumption_total'];
                }
            }
            if ($lastDate == date('Ymd')) {

                foreach ($plans_stats as $plan_id =>$plan){
                    if ($lastDateHour !=  date('Ymd'). '01'){
                        Plan ::where ('id', $plan_id)->increment('daily_consume',  $plan['consumption_total']/1000);
                    }else{
                        Plan ::where ('id', $plan_id)->update(['daily_consume'=>$plan['consumption_total']/1000]);
                    }
                }
                foreach ($ideas_stats as $idea_id =>$idea) {
                    if ($lastDateHour != date('Ymd'). '01'){
                        Idea ::where ('id', $idea_id)->increment('daily_consume',  $idea['consumption_total']/1000);
                    } else{
                        Idea ::where ('id', $idea_id)->update(['daily_consume' => $idea['consumption_total']/1000]);
                    }
                }
            }
            foreach ($users_stats as $user_id =>$user){
                Basic ::where ('id', $user_id)->increment('consume',  $user['consumption_total']/1000);
                Basic ::where ('id', $user_id)->increment('cost',     $user['cost']/1000);
            }
            Log :: info(__CLASS__. ' ok.', ['datetime'=>$dateTime]);
        }

    }
    protected function _cost($s)
    {
        if (!$s) return 0.0;
        $cmd = '/usr/bin/decrypter '. $s . " 2>&1";
        $errNo =0;
        $output = system($cmd, $errNo);
        if ($output <=0 || $errNo ==255 || !is_numeric($output)) return 0.0;
        echo "\n";

        return $output*1.0/10000;
    }
}
