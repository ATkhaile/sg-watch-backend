<?php

namespace App\Console\Commands;

use App\Enums\NotificationPushProcess;
use App\Models\NotificationPush;
use App\Services\NotificationPusherService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BatchPushMessageCommand extends Command
{
    protected $signature = 'batch-push-message';

    protected $description = 'Push scheduled notification_pushs messages';

    protected $notificationPusherService;

    public function __construct(NotificationPusherService $notificationPusherService)
    {
        parent::__construct();
        $this->notificationPusherService = $notificationPusherService;
    }

    public function handle(): int
    {
        $logger = Log::channel('log_notification_push');

        $logger->info('Starting batch push message process...');

        try {
            $now = Carbon::now();

            $ids = NotificationPush::query()
                ->where('push_now_flag', false)
                ->where('process', NotificationPushProcess::WAITING)
                ->whereNotNull('push_schedule')
                ->whereBetween('push_schedule', [
                    $now->copy()->startOfMinute(),
                    $now->copy()->endOfMinute()
                ])
                ->pluck('id');

            $logger->info('Found ' . $ids->count() . ' notification_pushs.');

            if ($ids->isEmpty()) {
                $logger->info('No notification_pushs to process.');
                return Command::SUCCESS;
            }

            $successCount = 0;
            $errorCount   = 0;

            foreach ($ids as $id) {
                try {
                    NotificationPush::whereKey($id)->update([
                        'process' => NotificationPushProcess::IN_PROGRESS,
                    ]);

                    $this->notificationPusherService->pushMessage($id);

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
            }

            $finishMsg = "Batch finished. success={$successCount}, error={$errorCount}";
            $logger->info($finishMsg);

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $logger->error('BatchPushMessageCommand fatal error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
