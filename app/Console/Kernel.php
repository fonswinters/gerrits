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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Run the queue listener every hour, if it is still running, do nothing
        $schedule->command('queue:listen')->hourly()->withoutOverlapping()->after(function() {
            \Log::debug('Cron / Kernel: php artisan queue:listen executed');
        });

        $schedule->command('kms:housekeeper')->dailyAt('04:00')->after(function() {
            \Log::debug('Cron / Kernel: php artisan kms:housekeeper executed');
        }); //Does housekeeping. Cleaning up old stuff and such.
    }
}
