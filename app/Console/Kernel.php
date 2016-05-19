<?php

namespace Pulse\Console;

use Illuminate\Console\Scheduling\Schedule;
use Pulse\Console\Commands\RunScheduledTransfers;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        RunScheduledTransfers::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('pulse:scheduled-transfers')
        ->everyMinute()
        ->appendOutputTo(storage_path('/tasks.txt'));
    }
}
