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
        // Run batch push notification every minute
        $schedule->command('batch-push-notification')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();
        $schedule->command('batch-push-message')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        // Process cron jobs every minute
        $schedule->command('process-cron-jobs')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        // Stripe同期スケジューラー（毎分実行、設定に基づいて実際の同期タイミングを判定）
        $schedule->command('stripe:sync')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        // Stripeキュー処理（毎分実行、pendingジョブを処理）
        $schedule->command('stripe:process-queue --limit=10')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();
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
