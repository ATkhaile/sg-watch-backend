<?php

namespace App\Console\Commands;

use App\Jobs\StripeSyncJob;
use App\Models\Stripe\StripeAccount;
use App\Models\Stripe\StripeSyncJob as StripeSyncJobModel;
use App\Models\Stripe\StripeSyncSettings;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StripeSyncCommand extends Command
{
    protected $signature = 'stripe:sync
                            {account_id? : 特定のアカウントIDを指定}
                            {--force : 同期タイミングを無視して強制実行}
                            {--queue : キューベースの処理を使用（全アカウント自動同期用）}
                            {--immediate : 即座にLaravelキューを使用（従来の動作）}';

    protected $description = 'Execute scheduled Stripe data synchronization';

    /**
     * 同期対象オブジェクト
     */
    private const SYNC_OBJECTS = [
        'customers',
        'products',
        'prices',
        'payment_intents',
        'charges',
        'subscriptions',
        'subscription_items',
        'invoices',
        'invoice_items',
        'refunds',
        'payment_methods',
        'payment_links',
        'checkout_sessions',
        'balance_transactions',
        'payouts',
        'disputes',
        'credit_notes',
        'events',
    ];

    public function handle(): int
    {
        $logger = Log::channel('stripe');
        $logger->info('Starting scheduled Stripe sync process...');

        try {
            $specificAccountId = $this->argument('account_id');
            $forceRun = $this->option('force');
            $useQueue = $this->option('queue');
            $immediate = $this->option('immediate');

            // 特定のアカウントが指定された場合
            if ($specificAccountId) {
                return $this->syncAccount((int) $specificAccountId, $logger, $forceRun);
            }

            // グローバル設定を確認
            $globalSettings = StripeSyncSettings::getGlobalSettings();

            if (!$globalSettings->auto_sync_enabled && !$forceRun) {
                $logger->info('Auto sync is disabled globally.');
                $this->info('Auto sync is disabled globally.');
                return Command::SUCCESS;
            }

            // 同期タイミングを確認（--forceオプションがない場合）
            if (!$forceRun && !$globalSettings->shouldSync()) {
                $nextSync = $globalSettings->next_auto_sync_at?->format('Y-m-d H:i:s') ?? 'not set';
                $logger->info("Not yet time for next sync. Next sync at: {$nextSync}");
                $this->info("Not yet time for next sync. Next sync at: {$nextSync}");
                return Command::SUCCESS;
            }

            // 全アクティブアカウントを取得
            $accounts = StripeAccount::where('status', 'active')->get();

            if ($accounts->isEmpty()) {
                $logger->info('No active Stripe accounts found.');
                $this->info('No active Stripe accounts found.');
                return Command::SUCCESS;
            }

            $this->info("Found {$accounts->count()} active accounts.");

            // --queueオプションまたはデフォルトでキューベースの処理を使用
            if ($useQueue || !$immediate) {
                return $this->enqueueAllAccountsSync($accounts, $logger, $globalSettings);
            }

            // 従来のLaravelキュー（即時ディスパッチ）を使用
            return $this->dispatchImmediateSync($accounts, $logger, $globalSettings);

        } catch (\Throwable $e) {
            $logger->error('Stripe sync command failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error('Stripe sync command failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * キューベースの同期（stripe_sync_jobsテーブルに登録）
     */
    private function enqueueAllAccountsSync($accounts, $logger, StripeSyncSettings $globalSettings): int
    {
        $logger->info('Using queue-based sync (stripe_sync_jobs table)');
        $this->info('Using queue-based sync...');

        $jobsCreated = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($accounts as $account) {
                // アカウント固有の設定を確認
                $accountSettings = StripeSyncSettings::getAccountSettings($account->id);

                // アカウント固有の設定で無効になっている場合はスキップ
                if ($accountSettings && !$accountSettings->auto_sync_enabled) {
                    $logger->info("Skipping account {$account->id} ({$account->display_name}): auto sync disabled");
                    $skippedCount++;
                    continue;
                }

                foreach (self::SYNC_OBJECTS as $objectName) {
                    // 同じアカウント・オブジェクトでpendingまたはin_progressのジョブがあればスキップ
                    $existingJob = StripeSyncJobModel::where('stripe_account_id', $account->id)
                        ->where('object_name', $objectName)
                        ->whereIn('status', [StripeSyncJobModel::STATUS_PENDING, StripeSyncJobModel::STATUS_IN_PROGRESS])
                        ->exists();

                    if ($existingJob) {
                        continue;
                    }

                    StripeSyncJobModel::create([
                        'stripe_account_id' => $account->id,
                        'object_name' => $objectName,
                        'status' => StripeSyncJobModel::STATUS_PENDING,
                        'job_type' => 'auto',
                        'scheduled_at' => null, // 即時実行可能
                        'creator' => 'scheduled',
                    ]);

                    $jobsCreated++;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $logger->error('Failed to enqueue sync jobs', [
                'message' => $e->getMessage(),
            ]);
            $this->error('Failed to enqueue sync jobs: ' . $e->getMessage());
            return Command::FAILURE;
        }

        // グローバル設定の次回同期時刻を更新
        $globalSettings->recordSyncExecution('scheduled');

        $logger->info("Queue-based sync jobs created. Created: {$jobsCreated}, Skipped accounts: {$skippedCount}");
        $this->info("Jobs created: {$jobsCreated}, Skipped accounts: {$skippedCount}");

        // キュー処理コマンドを呼び出す
        if ($jobsCreated > 0) {
            $this->info('Starting queue processing...');
            Artisan::call('stripe:process-queue', ['--limit' => 50]);
            $this->info(Artisan::output());
        }

        return Command::SUCCESS;
    }

    /**
     * 従来のLaravelキューを使用した即時同期
     */
    private function dispatchImmediateSync($accounts, $logger, StripeSyncSettings $globalSettings): int
    {
        $logger->info('Using immediate Laravel queue dispatch');
        $this->info('Using immediate Laravel queue dispatch...');

        $dispatchedCount = 0;
        $skippedCount = 0;

        foreach ($accounts as $account) {
            // アカウント固有の設定を確認
            $accountSettings = StripeSyncSettings::getAccountSettings($account->id);

            // アカウント固有の設定で無効になっている場合はスキップ
            if ($accountSettings && !$accountSettings->auto_sync_enabled) {
                $logger->info("Skipping account {$account->id} ({$account->display_name}): auto sync disabled");
                $skippedCount++;
                continue;
            }

            // 同期ジョブをディスパッチ
            StripeSyncJob::dispatch($account->id, 'incremental', 'scheduled');
            $logger->info("Dispatched sync job for account {$account->id} ({$account->display_name})");
            $this->info("Dispatched sync job for account {$account->id} ({$account->display_name})");
            $dispatchedCount++;
        }

        // グローバル設定の次回同期時刻を更新
        $globalSettings->recordSyncExecution('scheduled');

        $logger->info("Scheduled Stripe sync completed. Dispatched: {$dispatchedCount}, Skipped: {$skippedCount}");
        $this->info("Completed. Dispatched: {$dispatchedCount}, Skipped: {$skippedCount}");

        return Command::SUCCESS;
    }

    /**
     * 特定のアカウントを同期
     */
    private function syncAccount(int $accountId, $logger, bool $forceRun): int
    {
        $account = StripeAccount::find($accountId);

        if (!$account) {
            $logger->error("Account not found: {$accountId}");
            $this->error("Account not found: {$accountId}");
            return Command::FAILURE;
        }

        if ($account->status !== 'active' && !$forceRun) {
            $logger->warning("Account {$accountId} is not active: {$account->status}");
            $this->warn("Account {$accountId} is not active: {$account->status}");
            return Command::SUCCESS;
        }

        // 同期ジョブをディスパッチ（従来の即時同期）
        StripeSyncJob::dispatch($accountId, 'incremental', 'manual');
        $logger->info("Dispatched sync job for account {$accountId} ({$account->display_name})");
        $this->info("Dispatched sync job for account {$accountId} ({$account->display_name})");

        return Command::SUCCESS;
    }
}
