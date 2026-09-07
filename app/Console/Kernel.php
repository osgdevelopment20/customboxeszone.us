<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // Push recently changed URLs to the Google Indexing API once a day.
        if (config('google-indexing.schedule.enabled')) {
            $schedule->command('google:index', [
                '--source' => config('google-indexing.schedule.source', 'database'),
                '--since'  => config('google-indexing.schedule.since', 2),
                '--limit'  => config('google-indexing.schedule.limit', 200),
            ])->dailyAt(config('google-indexing.schedule.time', '03:00'))->withoutOverlapping();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
