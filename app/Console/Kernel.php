<?php

namespace App\Console;

use App\Console\Commands\Shop\FulfillmentService;
use App\Console\Commands\Shop\InventoryConnect;
use App\Console\Commands\Shop\Loader;
use App\Console\Commands\Shop\Webhook;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Loader::class,
        Webhook::class,
        FulfillmentService::class,
        InventoryConnect::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
