<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\ConsumptionDaily;
use App\Idea;
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
    protected function _stat($hour)
    {
        $p = '#^(?P<ip>\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3})\s-\s(.*)\s\[(?P<datetime>.*)\]\s\"(?P<request>.*?)"\s(\d{3,})\s(\d+)\s\"([^\s]*)\"\s\"(.*?)\"\s\"(.*)\"$#ui' ;
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
                            }
                            else $items[$id][$item['type']] = 1;
                            foreach (['region', 'classification', 'operator', 'device_type', 'network'] as $key) {
                                if (isset($item[$key]) && !isset($items[$id][$key]))  $items[$id][$key] = $item[$key] ;
                            }
                        }else{
                            unset($item['id']);
                            $items [$id] = $item;
                            $items[$id][$item['type']] = 1;
                        }
                    }
                }
            }
            fclose($fp);
        }
        $charges = $this->_charge($hour);
        foreach ($charges as $sessionId =>$price) {
            $items [$sessionId]['price'] = $price;
        }
        $stats = [];
	foreach ($items  as $sessionId=>$item) {
if (!isset($item['idea_id'])) continue;
		foreach (['App\Region'=>'region', 'App\Classification'=>'classification', 'App\Operator'=>'operator', 'App\Device'=>'device_type', 'App\Network'=>'network'] as $consumable_type=>$consumable_key) {
			$consumable_value = isset($item[$consumable_key]) && $item[$consumable_key]!= 'None' ? $item[$consumable_key] : '0' ;
			$stat_key = sprintf('%d_%s_%s', $item['idea_id'], $consumable_type, $consumable_value); 
			if (isset($stats[$stat_key])) {
				foreach (['show'=>'exhibition_total', 'click'=>'click_total', 'download'=>'download_total', 'open'=>'open_total', 'install'=>'install_total', 'price'=>'consumption_total'] as $key=>$value) {
					if (isset($item[$key]) && $key == 'price') {
						$stats[$stat_key][$value] += $item['price'];
					}elseif (isset($item[$key])){
						$stats[$stat_key][$value] += 1;
                                        }
				}
			}else{
				$stats[$stat_key] = ['idea_id' => $item['idea_id'], 'consumable_id'=> $consumable_value, 'consumable_type'=>$consumable_type ];
				if ($consumable_key == 'region') {
					if (strpos($consumable_value, ',') == false) {
						$consumable_id = $consumable_value;
						$parent_id     = 0;
					}else{
						list($parent_id, $consumable_id) = explode(',', $consumable_value);
					}
					$stats[$stat_key]['consumable_id'] = $consumable_id;
					$stats[$stat_key]['parent_id']    = $parent_id;
				}
                                 $stats[$stat_key]['consumable_id'] = intval( $stats[$stat_key]['consumable_id']);
				foreach (['show'=>'exhibition_total', 'click'=>'click_total', 'download'=>'download_total','open'=>'open_total', 'install'=>'install_total', 'price'=>'consumption_total'] as $key=>$value) {
					if (isset($item[$key]) && $key == 'price') {
						$stats[$stat_key][$value] = $item['price'];
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
        $endTime     = date('Y-m-d H:00:00' );
        $startTime   = date('Y-m-d H:00:00' , $time - 3600* $i);
        $dateTime    = date('Y-m-d H',  $time - 3600 * $i);
        $date        = date('Y-m-d',  $time - 3600 *$i);
        $lastDateHour = date('YmdH', $time - 3600 *$i);
        $stats        = $this->_stat($lastDateHour);
       
        $ideas  = array();
	foreach (array_unique(array_column($stats, 'idea_id')) as $id) {
		$c   = Idea ::where('id', $id)->select(['id', 'user_id', 'plan_id', 'status'])->first();

		$ideas[$id]= $c->toArray();
	}
       $install_total = 0;
        foreach ($stats  as $stat) {
            $consumption =  collect($stat+$ideas[$stat['idea_id']]);
            ConsumptionDaily :: where('idea_id'        ,  $consumption->get('idea_id'))
                -> where('consumable_type',  $consumption->get('consumable_type')) 
                -> where('parent_id'  ,  $consumption->get('parent_id', 0))
                -> where('consumable_id'  ,  $consumption->get('consumable_id'))
                -> where('datetime'       ,  $dateTime)
                ->delete(); 
            ConsumptionDaily ::create($consumption->all() + array('datetime'=>$dateTime, 'date'=>$date));
        }
        Log :: info(__CLASS__. ' ok.', ['datetime'=>$dateTime]);
}

    }
}
