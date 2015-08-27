<?php

namespace App\Console\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Console\Command;
use Log;
use DB;
use App\Media;
class LoadData extends Command implements SelfHandling
{
    /**
     * signature 
     * 
     * @var string
     * @access protected
     */
    protected $signature = 'loadData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load Data';
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
        $inputFile = '/tmp/app_class_result';
        $lines = file($inputFile);
        $items = [];
        foreach ($lines as $line) {
            $line = trim($line);
            $temp = explode("\t", $line);
            $items[$temp[4]] ['device_media'][0] = ['adx'=> $temp[4], 'link'=>$temp[7], 'device_id'=>3, 'group'=>$temp[6]];
            $classification = new \stdClass();
            $classification->parent  =  $temp[1];
            $classify = []; 

            do {
                $classification = DB :: table('classification')->find($classification->parent);
                if (!isset($classification->id)) {
                    break;
                }
                array_unshift ($classify, $classification->id);
            } while ($classification->parent !=0) ;

            $map = [0=>'classify_id', 1=>'classify_id_1', 2=>'classify_id_3'];
            foreach ($map as $value) {
                $items[$temp[4]][$value] = 0;
            }

            $items[$temp[4]]['name']= $temp[0];
            foreach($classify as $i => $classify_id) {
                $items[$temp[4]][$map[$i]] =  $classify_id;
            }

            $device_media = DB::table('device_media')->where('adx', $temp[4])->get();
            if ($device_media) continue;
            $media  = Media ::where('name', $temp[0])
                      ->where ('classify_id', $items[$temp[4]]['classify_id'])
                      ->where ('classify_id_1', $items[$temp[4]]['classify_id_1'])
                      ->where ('classify_id_3', $items[$temp[4]]['classify_id_3'])
                      ->first();
            if ($media) {
                $items[$temp[4]]['device_media'][0]['media_id'] = $media->id;
                DB :: table('device_media')->insert($items[$temp[4]]['device_media'][0]);
                    echo $line. "\n";
            }else{

                try {
                    Media ::create ($items[$temp[4]]);
                }catch(\Exception $e){
                    echo $line. "\n";
                }
            }

        }
       // var_dump($items);

        //
    }
}
