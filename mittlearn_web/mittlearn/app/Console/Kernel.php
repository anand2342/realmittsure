<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('subscriptions:update-status')->hourly();
        $schedule->command('access-code:update-status')->daily();
        $schedule->command('update:online-class-status')->everyMinute();
        $schedule->command('test-papers:deactivate-expired')->everyMinute();

        $schedule->command('sessions:autologout')->everyFifteenMinutes();

        // // commented now for Mittsure ERP to Mittlearn LMS data sync
        // $schedule->command('sync:erp-data')->everyThirtyMinutes();

    }



    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
