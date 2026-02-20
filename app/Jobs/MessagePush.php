<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\NotificationPush;
use App\Models\UserNotificationHistory;
use App\Models\UserNotificationPush;
use App\Services\NotificationPusherService;
use Illuminate\Support\Facades\Log;
use App\Enums\NotificationPushProcess;

class MessagePush implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $notificationId,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationPusherService $notificationPusherService): void
    {
        $logger = Log::channel('log_notification_push');

        $logger->info('Starting batch push message process...');

        try {
            $successCount = 0;
            $errorCount   = 0;
            $id = $this->notificationId;
            try {
                NotificationPush::whereKey($id)->update([
                    'process' => NotificationPushProcess::IN_PROGRESS,
                ]);

                $notificationPusherService->pushMessage($id);

                NotificationPush::whereKey($id)->update([
                    'process' => NotificationPushProcess::SUCCESSFULLY,
                ]);

                $successCount++;
                $msg = "Successfully processed notification_push id={$id}";
                $logger->info($msg);
            } catch (\Throwable $e) {
                NotificationPush::whereKey($id)->update([
                    'process' => NotificationPushProcess::WAITING,
                ]);

                $errorCount++;

                $logger->error('BatchPushMessageCommand error', [
                    'notification_push_id' => $id,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

        } catch (\Throwable $e) {
            $logger->error('BatchPushMessageCommand fatal error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
    }
}
