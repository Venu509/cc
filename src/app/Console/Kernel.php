<?php

namespace App\Console;

use App\Console\Commands\AutoClockOut;
use App\Console\Commands\Initialize;
use App\Console\Commands\SyncPermissions;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Initialize::class,
        SyncPermissions::class,
        AutoClockOut::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:auto-clock-out')->dailyAt('12:05');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
