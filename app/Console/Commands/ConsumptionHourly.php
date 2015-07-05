<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Exhibition;
use App\ConsumptionDaily;
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
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        //
        $time        =      time();
        $endTime     = date('Y-m-d H:00:00');
        $startTime   = date('Y-m-d H:00:00' , $time - 3600);
        $dateTime    = date('Y-m-d H',  $time - 3600);
        $date        = date('Y-m-d',  $time - 3600);
        foreach (['industry_id'=>'App\Industry', 'interest'=>'App\Category', 'age'=>'App\Age',
                  'region_id'=>'App\Region', 'operator_id'=>'App\Operator', 'network'=>'App\Network',
                  'os' =>'App\Os', 'gender'=>'App\Gender', 'device' =>'App\Device', 'app_type'=>'App\Classification'
          ] as $groupByField =>$consumableType) {
            $consumption_daily = collect([]);
            foreach(['updated_at'=>'exhibition', 'clicked_at'=>'click', 'consumed_at'=> 'consumption',
                    'open_at'=>'open_total', 'install_at'=>'install_total', 'download_at'=>'download_total'] as $whereField=>$total) {

                $exhibition  =  new Exhibition();
                $collections = $exhibition-> where($whereField, '>=', $startTime)
                    ->where($whereField, '<', $endTime)
                    ->select($groupByField, 'plan_id', 'idea_id', 'user_id', DB::raw($whereField == 'consumed_at' ? 'sum(price) as total':'count(1) as total'))
                    ->groupBy('idea_id') 
                    ->groupBy($groupByField) 
                    ->get();
                foreach ($collections as $c) {
                    $daily = [];
                    $daily['consumable_id']  = $c->$groupByField;
                    $daily['idea_id']      = $c->idea_id;
                    $daily['plan_id']      = $c->plan_id;
                    $daily['user_id']      = $c->user_id;
                    $daily[$total]         = $c->total;
                    $key = sprintf('%d_%s_%d', $c->idea_id, $consumableType, $c->$groupByField);
                    if ($consumption_daily->has($key)){
                        $consumption_daily->get($key)->put('consumable_id', $c->$groupByField);
                    }else{
                        $consumption_daily->put($key, collect($daily));
                    }
                }
            }
            foreach ($consumption_daily as $consumption) {
                ConsumptionDaily :: where('idea_id'        ,  $consumption->idea_id)
                    -> where('consumable_type',  $consumableType) 
                    -> where('consumable_id'  ,  $consumption->consumable_id)
                    -> where('datetime'       ,  $dateTime)
                    ->delete(); 
                ConsumptionDaily ::create($consumption->all() + array('datetime'=>$dateTime, 'consumable_type'=>$consumableType, 'date'=>$date));
            }

        }
        Log :: info('ok.', ['datetime'=>$dateTime]);

    }
}
