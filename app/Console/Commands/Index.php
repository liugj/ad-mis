<?php

namespace App\Console\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Console\Command;
use Log;
class Index extends Command implements SelfHandling
{
    /**
     * signature 
     * 
     * @var string
     * @access protected
     */
    protected $signature = 'index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index';
    const INDEXCMD ='/opt/app/xunsearch/sdk/php/util/Indexer.php';
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
        
        if ('prod' == env('APP_ENV')){
            $cmd = sprintf('%s  --source=mysql://%s:%s@127.0.0.1:%d/mis --sql="select dm.id as id , name,comment,device_id,media_id,classify_id, classify_id_1,classify_id_3,group_id from medias  as m join device_media  as dm on dm.media_id=m.id" media  --rebuild',self :: INDEXCMD, env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_PORT'));
            system($cmd);
            log :: info (env('APP_ENV'). ' '. $cmd);
            }else{
            log :: info (env('APP_ENV'). 'not run.');
        }
    }
}
