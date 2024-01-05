<?php

namespace DevPirate\LaraExcelCraft\Console;

use DevPirate\LaraExcelCraft\Console\Commands\RemoveExpiredFilesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        RemoveExpiredFilesCommand::class
    ];

    protected function scheduleTimezone()
    {
        return 'UTC'; // Adjust the timezone as needed
    }

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('files:clean')->hourly();
    }
}
