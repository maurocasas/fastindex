<?php

use App\Console\Commands\MonitorBrokenLinks;
use App\Console\Commands\MonitorServiceAccounts;
use App\Console\Commands\DispatchIndexingBatches;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(MonitorServiceAccounts::class)
            ->twiceDaily();

        $schedule->command(DispatchIndexingBatches::class)
            ->dailyAt('00:00');

        $schedule->command(MonitorBrokenLinks::class)
            ->twiceDaily();
    })
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
