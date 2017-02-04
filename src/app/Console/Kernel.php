<?php

namespace App\Console;

use App\Console\Commands\KafkaPubTest;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands
        = [
	        \Laravelista\LumenVendorPublish\VendorPublishCommand::class,
            \Prettus\Repository\Generators\Commands\TransformerCommand::class,
            \Prettus\Repository\Generators\Commands\CriteriaCommand::class,
            \Prettus\Repository\Generators\Commands\RepositoryCommand::class,
            \App\Console\Commands\Repository::class,
            \App\Console\Commands\RouteList::class,
            \App\Console\Commands\ApiRoutes::class,
            KafkaPubTest::class
        ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

    }
}
