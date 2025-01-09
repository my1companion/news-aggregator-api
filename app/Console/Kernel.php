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
        // Register your custom commands here
        \App\Console\Commands\FetchNewsApiArticles::class,
        \App\Console\Commands\FetchGuardianArticles::class,
        \App\Console\Commands\FetchNytimesArticles::class,
        \App\Console\Commands\MakeService::class, // Optional: if you added the custom service generator
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Schedule commands to fetch articles from each source
        $schedule->command('fetch:newsapi')->hourly();
        $schedule->command('fetch:guardian')->hourly();
        $schedule->command('fetch:nytimes')->hourly();

        // Example: Additional scheduled commands can be added here
        // $schedule->command('another:command')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        // Load commands from the Console/Commands directory
        $this->load(__DIR__ . '/Commands');

        // Optionally include commands in routes/console.php
        require base_path('routes/console.php');
    }
}
