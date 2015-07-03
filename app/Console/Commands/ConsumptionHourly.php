<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;

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
        var_dump(__CLASS__);
    }
}
