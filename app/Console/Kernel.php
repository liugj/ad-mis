<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\Index::class,
        \App\Console\Commands\LoadData::class,
        \App\Console\Commands\ConsumptionHourly::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('consumption_hourly')
            ->cron('10 */1 * * *')
            ->sendOutputTo(storage_path(). '/logs/cron.log');
            
        $schedule->command('index')
            ->cron('*/10 * * * *');
    }
}
