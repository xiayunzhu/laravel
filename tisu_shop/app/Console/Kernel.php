<?php

namespace App\Console;

use App\Console\Commands\DailyOrderDate;
use App\Console\Commands\Buyers;
use App\Console\Commands\GoodPull;
use App\Console\Commands\RefundProcess;
use App\Console\Commands\StockPull;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //php artisan schedule:run
        ## 输出文件
        $filePath = storage_path('logs/laravel-' . date('Y-m-d') . '.log');

        ##定时任务- 生产环境
        if (config('app.env') == 'production') {
            $schedule->command(Buyers::class)->daily()->appendOutputTo($filePath);
        } else {
            $schedule->command(Buyers::class)->daily()->appendOutputTo($filePath);
            $schedule->command(RefundProcess::class)->hourly()->appendOutputTo($filePath);
            $schedule->command(DailyOrderDate::class)->daily()->appendOutputTo($filePath);
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
