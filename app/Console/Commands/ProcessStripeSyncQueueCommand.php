<?php

namespace App\Console\Commands;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Models\Stripe\StripeSyncJob;
use App\Models\Stripe\StripeAccount;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessStripeSyncQueueCommand extends Command
{
    protected $signature = 'stripe:process-queue
                            {--limit=10 : 1回の実行で処理するジョブ数}
                            {--timeout=300 : 各ジョブのタイムアウト秒数}';

    protected $description = 'Process pending Stripe sync jobs from the queue';

    public function __construct(
        private StripeSyncService $syncService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $logger = Log::channel('stripe');
        $logger->info('Starting Stripe sync queue processing...');

        $limit = (int) $this->option('limit');
        $timeout = (int) $this->option('timeout');

        try {
            // 実行可能なpendingジョブを取得
            $jobs = StripeSyncJob::where('status', StripeSyncJob::STATUS_PENDING)
                ->where(function ($query) {
                    $query->whereNull('scheduled_at')
                        ->orWhere('scheduled_at', '<=', now());
                })
                ->orderBy('created_at', 'asc')
                ->limit($limit)
                ->get();

            if ($jobs->isEmpty()) {
                $logger->info('No pending jobs to process.');
                $this->info('No pending jobs to process.');
                return Command::SUCCESS;
            }

            $this->info("Found {$jobs->count()} jobs to process.");

            $processedCount = 0;
            $errorCount = 0;

            foreach ($jobs as $job) {
                $startTime = microtime(true);

                try {
                    // ジョブを実行中にマーク
                    $job->update([
                        'status' => StripeSyncJob::STATUS_IN_PROGRESS,
                        'started_at' => now(),
                    ]);

                    $logger->info("Processing job {$job->id}: {$job->object_name} for account {$job->stripe_account_id}");
                    $this->info("Processing job {$job->id}: {$job->object_name}");

                    // アカウントを取得
                    $account = StripeAccount::find($job->stripe_account_id);

                    if (!$account) {
                        throw new \Exception("Account not found: {$job->stripe_account_id}");
                    }

                    if ($account->status !== 'active') {
                        throw new \Exception("Account is not active: {$account->status}");
                    }

                    // 同期を実行
                    $result = $this->syncService->backfill(
                        $account->id,
                        $job->object_name,
                        $job->creator ?? 'queue'
                    );

                    $objectResult = $result[$job->object_name] ?? null;

                    if ($objectResult && isset($objectResult['success']) && $objectResult['success']) {
                        // 成功
                        $job->update([
                            'status' => StripeSyncJob::STATUS_COMPLETED,
                            'finished_at' => now(),
                            'processed_count' => $objectResult['processed_count'] ?? 0,
                            'error_count' => 0,
                            'message' => null,
                        ]);

                        $processedCount++;
                        $logger->info("Job {$job->id} completed successfully. Processed: " . ($objectResult['processed_count'] ?? 0));
                        $this->info("  Completed. Processed: " . ($objectResult['processed_count'] ?? 0));
                    } else {
                        // 失敗
                        $errorMessage = $objectResult['error'] ?? 'Unknown error';
                        $job->update([
                            'status' => StripeSyncJob::STATUS_FAILED,
                            'finished_at' => now(),
                            'processed_count' => 0,
                            'error_count' => 1,
                            'message' => $errorMessage,
                        ]);

                        $errorCount++;
                        $logger->warning("Job {$job->id} failed: {$errorMessage}");
                        $this->warn("  Failed: {$errorMessage}");
                    }

                } catch (\Throwable $e) {
                    // 例外発生時
                    $job->update([
                        'status' => StripeSyncJob::STATUS_FAILED,
                        'finished_at' => now(),
                        'processed_count' => 0,
                        'error_count' => 1,
                        'message' => $e->getMessage(),
                    ]);

                    $errorCount++;
                    $logger->error("Job {$job->id} failed with exception", [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    $this->error("  Error: {$e->getMessage()}");
                }

                // タイムアウトチェック
                $elapsed = microtime(true) - $startTime;
                if ($elapsed > $timeout) {
                    $logger->warning("Job timeout exceeded: {$elapsed}s > {$timeout}s");
                }
            }

            $logger->info("Queue processing completed. Processed: {$processedCount}, Errors: {$errorCount}");
            $this->info("Completed. Processed: {$processedCount}, Errors: {$errorCount}");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $logger->error('Queue processing failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error('Queue processing failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
