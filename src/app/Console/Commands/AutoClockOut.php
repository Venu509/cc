<?php

namespace App\Console\Commands;

use Domain\Global\Actions\AutoClockOutAction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use JsonException;

class AutoClockOut extends Command
{
    protected $signature = 'app:auto-clock-out';

    protected $description = 'Auto Clock out users after some defined hours';

    /**
     * @throws JsonException
     */
    public function handle(): void
    {
        Log::channel('cron')->info('Started Auto Clock Out');

        (new AutoClockOutAction())->execute();

        Log::channel('cron')->info('Ended Auto Clock Out after');
    }
}
