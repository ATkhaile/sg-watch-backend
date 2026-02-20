<?php

namespace App\Console\Commands;

use App\Services\NotificationPusherService;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BatchPushNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch-push-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batch push notifications to users';

    /**
     * The notification pusher service.
     *
     * @var NotificationPusherService
     */
    protected $notificationPusherService;

    /**
     * Create a new command instance.
     *
     * @param NotificationPusherService $notificationPusherService
     * @return void
     */
    public function __construct(NotificationPusherService $notificationPusherService)
    {
        parent::__construct();
        $this->notificationPusherService = $notificationPusherService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting batch push notification process...');

        try {
            $start = Carbon::now()->startOfMinute();
            $end = Carbon::now()->endOfMinute();

            $this->info("Fetching notifications scheduled between {$start} and {$end}...");

            $notificationIds = Notification::where('push_now_flag', false)
                ->whereBetween('push_datetime', [$start, $end])
                ->pluck('id');

            $this->info("Found {$notificationIds->count()} notifications to process.");

            $successCount = 0;
            $errorCount = 0;

            foreach ($notificationIds as $id) {
                try {
                    $this->info("Processing notification ID: {$id}");

                    $this->notificationPusherService->pushNotification($id);

                    $successCount++;
                    $this->info("Successfully processed notification ID: {$id}");
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->error("Error processing notification ID {$id}: " . $e->getMessage());
                    Log::error("Error processing notification ID {$id}: " . $e->getMessage());
                }
            }

            $this->info("Batch push notification process completed. Success: {$successCount}, Errors: {$errorCount}");
            Log::info('Batch push notification command executed successfully', [
                'total_notifications' => $notificationIds->count(),
                'success_count' => $successCount,
                'error_count' => $errorCount
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error occurred during batch push notification: ' . $e->getMessage());
            Log::error('Batch push notification command failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
