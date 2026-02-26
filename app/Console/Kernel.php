<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    #[\Override]
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('labels:clean')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    #[\Override]
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
