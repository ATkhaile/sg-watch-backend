<?php

namespace App\Jobs;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Models\Stripe\StripeAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class StripeSyncJob implements ShouldQueue
{
    use Queueable;

    /**
     * ジョブの最大試行回数
     */
    public int $tries = 3;

    /**
     * ジョブのタイムアウト秒数
     */
    public int $timeout = 3600; // 1時間

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $accountId,
        private string $syncType = 'incremental',
        private string $creator = 'system',
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(StripeSyncService $syncService): void
    {
        $logger = Log::channel('stripe');

        $logger->info("Starting {$this->syncType} sync job for account {$this->accountId}");

        try {
            $account = StripeAccount::find($this->accountId);

            if (!$account) {
                $logger->error("Account not found: {$this->accountId}");
                return;
            }

            $logger->info("Syncing account: {$account->display_name} (ID: {$this->accountId})");

            if ($this->syncType === 'backfill') {
                $results = $syncService->backfill($this->accountId, null, $this->creator);
            } else {
                $results = $syncService->incrementalSync($this->accountId, null, $this->creator);
            }

            // 結果をログに記録
            $successCount = 0;
            $errorCount = 0;

            foreach ($results as $objectType => $result) {
                if (isset($result['success']) && $result['success']) {
                    $successCount++;
                    $processed = $result['processed_count'] ?? 0;
                    $logger->info("Synced {$objectType}: {$processed} records");
                } else {
                    $errorCount++;
                    $error = $result['error'] ?? 'Unknown error';
                    $logger->warning("Failed to sync {$objectType}: {$error}");
                }
            }

            $logger->info("Sync job completed for account {$this->accountId}. Success: {$successCount}, Errors: {$errorCount}");

        } catch (\Throwable $e) {
            $logger->error("Sync job failed for account {$this->accountId}", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // 再試行のためにジョブを失敗させる
            throw $e;
        }
    }

    /**
     * ジョブが失敗した場合の処理
     */
    public function failed(\Throwable $exception): void
    {
        $logger = Log::channel('stripe');

        $logger->error("Sync job permanently failed for account {$this->accountId}", [
            'message' => $exception->getMessage(),
            'sync_type' => $this->syncType,
            'creator' => $this->creator,
        ]);
    }
}
