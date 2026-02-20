<?php

namespace App\Domain\Stripe\Service;

use App\Models\Stripe\StripeSyncJob;
use App\Models\Stripe\StripeSyncSettings;
// Core models
use App\Models\Stripe\StripeCustomer;
use App\Models\Stripe\StripeProduct;
use App\Models\Stripe\StripePrice;
use App\Models\Stripe\StripePaymentIntent;
use App\Models\Stripe\StripeCharge;
use App\Models\Stripe\StripeSubscription;
use App\Models\Stripe\StripeSubscriptionItem;
use App\Models\Stripe\StripeInvoice;
use App\Models\Stripe\StripeInvoiceItem;
use App\Models\Stripe\StripeRefund;
use App\Models\Stripe\StripePaymentMethod;
use App\Models\Stripe\StripePaymentLink;
use App\Models\Stripe\StripeCheckoutSession;
use App\Models\Stripe\StripeBalanceTransaction;
use App\Models\Stripe\StripePayout;
use App\Models\Stripe\StripeDispute;
use App\Models\Stripe\StripeCreditNote;
use App\Models\Stripe\StripeEvent;
use App\Models\Stripe\StripeCustomerBalanceTransaction;
// Extended models
use App\Models\Stripe\StripeCoupon;
use App\Models\Stripe\StripePromotionCode;
use App\Models\Stripe\StripeSetupIntent;
use App\Models\Stripe\StripeQuote;
use App\Models\Stripe\StripeSubscriptionSchedule;
use App\Models\Stripe\StripeTaxRate;
use App\Models\Stripe\StripeTaxCode;
use App\Models\Stripe\StripeShippingRate;
use App\Models\Stripe\StripeFile;
// Issuing models
use App\Models\Stripe\StripeIssuingCardholder;
use App\Models\Stripe\StripeIssuingCard;
use App\Models\Stripe\StripeIssuingAuthorization;
use App\Models\Stripe\StripeIssuingTransaction;
use App\Models\Stripe\StripeIssuingDispute;
// Terminal models
use App\Models\Stripe\StripeTerminalLocation;
use App\Models\Stripe\StripeTerminalReader;
use App\Models\Stripe\StripeTerminalConnectionToken;
// Identity/Radar models
use App\Models\Stripe\StripeVerificationSession;
use App\Models\Stripe\StripeVerificationReport;
use App\Models\Stripe\StripeRadarValueList;
use App\Models\Stripe\StripeRadarValueListItem;
// Financial/Treasury models
use App\Models\Stripe\StripeBalance;
use App\Models\Stripe\StripeAccountSession;
use App\Models\Stripe\StripeFinancialConnectionsAccount;
use App\Models\Stripe\StripeFinancialConnectionsSession;
use App\Models\Stripe\StripeTreasuryFinancialAccount;
use App\Models\Stripe\StripeTreasuryTransaction;
// Reporting models
use App\Models\Stripe\StripeSigmaScheduledQueryRun;
use App\Models\Stripe\StripeReportingReportRun;
// Additional models
use App\Models\Stripe\StripeCountrySpec;
use App\Models\Stripe\StripeMandate;
use App\Models\Stripe\StripeSource;
use App\Models\Stripe\StripeToken;
use App\Models\Stripe\StripePaymentRecord;
use App\Models\Stripe\StripePerson;
use App\Models\Stripe\StripeCapability;
use App\Models\Stripe\StripeAccountLink;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Stripe同期モニタリングサービス
 * 統計情報、キュー管理、DB統計を提供
 */
class StripeSyncMonitoringService
{
    /**
     * DBに保存されているStripeオブジェクトのモデル一覧
     */
    private const STRIPE_MODELS = [
        // Core payment objects
        'customers' => StripeCustomer::class,
        'products' => StripeProduct::class,
        'prices' => StripePrice::class,
        'payment_intents' => StripePaymentIntent::class,
        'charges' => StripeCharge::class,
        'subscriptions' => StripeSubscription::class,
        'subscription_items' => StripeSubscriptionItem::class,
        'invoices' => StripeInvoice::class,
        'invoice_items' => StripeInvoiceItem::class,
        'refunds' => StripeRefund::class,
        'payment_methods' => StripePaymentMethod::class,
        'payment_links' => StripePaymentLink::class,
        'checkout_sessions' => StripeCheckoutSession::class,
        // Finance/Balance objects
        'balance_transactions' => StripeBalanceTransaction::class,
        'balances' => StripeBalance::class,
        'payouts' => StripePayout::class,
        'disputes' => StripeDispute::class,
        'credit_notes' => StripeCreditNote::class,
        'customer_balance_transactions' => StripeCustomerBalanceTransaction::class,
        // Events
        'events' => StripeEvent::class,
        // Extended objects
        'coupons' => StripeCoupon::class,
        'promotion_codes' => StripePromotionCode::class,
        'setup_intents' => StripeSetupIntent::class,
        'quotes' => StripeQuote::class,
        'subscription_schedules' => StripeSubscriptionSchedule::class,
        'tax_rates' => StripeTaxRate::class,
        'tax_codes' => StripeTaxCode::class,
        'shipping_rates' => StripeShippingRate::class,
        'files' => StripeFile::class,
        // Issuing objects
        'issuing_cardholders' => StripeIssuingCardholder::class,
        'issuing_cards' => StripeIssuingCard::class,
        'issuing_authorizations' => StripeIssuingAuthorization::class,
        'issuing_transactions' => StripeIssuingTransaction::class,
        'issuing_disputes' => StripeIssuingDispute::class,
        // Terminal objects
        'terminal_locations' => StripeTerminalLocation::class,
        'terminal_readers' => StripeTerminalReader::class,
        'terminal_connection_tokens' => StripeTerminalConnectionToken::class,
        // Identity/Radar objects
        'verification_sessions' => StripeVerificationSession::class,
        'verification_reports' => StripeVerificationReport::class,
        'radar_value_lists' => StripeRadarValueList::class,
        'radar_value_list_items' => StripeRadarValueListItem::class,
        // Financial Connections/Treasury
        'account_sessions' => StripeAccountSession::class,
        'financial_connections_accounts' => StripeFinancialConnectionsAccount::class,
        'financial_connections_sessions' => StripeFinancialConnectionsSession::class,
        'treasury_financial_accounts' => StripeTreasuryFinancialAccount::class,
        'treasury_transactions' => StripeTreasuryTransaction::class,
        // Reporting
        'sigma_scheduled_query_runs' => StripeSigmaScheduledQueryRun::class,
        'reporting_report_runs' => StripeReportingReportRun::class,
        // Additional objects
        'country_specs' => StripeCountrySpec::class,
        'mandates' => StripeMandate::class,
        'sources' => StripeSource::class,
        'tokens' => StripeToken::class,
        'payment_records' => StripePaymentRecord::class,
        'persons' => StripePerson::class,
        'capabilities' => StripeCapability::class,
        'account_links' => StripeAccountLink::class,
    ];

    /**
     * 同期設定を取得
     */
    public function getSettings(?int $accountId = null): StripeSyncSettings
    {
        if ($accountId) {
            $settings = StripeSyncSettings::getAccountSettings($accountId);
            if ($settings) {
                return $settings;
            }
        }

        return StripeSyncSettings::getGlobalSettings();
    }

    /**
     * 同期設定を更新
     */
    public function updateSettings(array $data, ?string $updater = 'api'): StripeSyncSettings
    {
        $accountId = $data['stripe_account_id'] ?? null;

        $settings = $accountId
            ? StripeSyncSettings::firstOrCreate(
                ['stripe_account_id' => $accountId],
                ['creator' => $updater]
            )
            : StripeSyncSettings::getGlobalSettings();

        $updateData = array_filter([
            'auto_sync_enabled' => $data['auto_sync_enabled'] ?? null,
            'webhook_enabled' => $data['webhook_enabled'] ?? null,
            'sync_frequency' => $data['sync_frequency'] ?? null,
            'updater' => $updater,
        ], fn($value) => !is_null($value));

        // auto_sync_enabledがtrueに変更された場合、次回同期時刻を設定
        if (isset($updateData['auto_sync_enabled']) && $updateData['auto_sync_enabled'] && !$settings->next_auto_sync_at) {
            $settings->sync_frequency = $updateData['sync_frequency'] ?? $settings->sync_frequency;
            $updateData['next_auto_sync_at'] = $settings->calculateNextSyncAt();
        }

        $settings->update($updateData);

        return $settings->fresh();
    }

    /**
     * チャート用同期統計を取得
     */
    public function getSyncStats(string $view, ?int $accountId = null): array
    {
        $query = StripeSyncJob::query()
            ->whereIn('status', [StripeSyncJob::STATUS_COMPLETED, StripeSyncJob::STATUS_FAILED]);

        if ($accountId) {
            $query->where('stripe_account_id', $accountId);
        }

        // 期間とグループ化の設定
        [$startDate, $dateFormat, $labels] = $this->getDateRangeConfig($view);

        $query->where('finished_at', '>=', $startDate);

        // 日付ごとに集計
        $results = $query
            ->select([
                DB::raw("DATE_FORMAT(finished_at, '{$dateFormat}') as period"),
                DB::raw('SUM(processed_count) as total_synced'),
                DB::raw('SUM(error_count) as errors'),
            ])
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        // ラベルごとのデータを構築
        $totalSynced = [];
        $errors = [];
        $totalRecordsSynced = 0;
        $totalErrors = 0;

        foreach ($labels as $label => $period) {
            $data = $results->get($period);
            $synced = (int) ($data->total_synced ?? 0);
            $error = (int) ($data->errors ?? 0);

            $totalSynced[] = $synced;
            $errors[] = $error;
            $totalRecordsSynced += $synced;
            $totalErrors += $error;
        }

        $successRate = $totalRecordsSynced > 0
            ? round(($totalRecordsSynced - $totalErrors) / $totalRecordsSynced * 100, 2)
            : 100;

        return [
            'view' => $view,
            'labels' => array_keys($labels),
            'datasets' => [
                'total_synced' => $totalSynced,
                'errors' => $errors,
            ],
            'summary' => [
                'total_records_synced' => $totalRecordsSynced,
                'total_errors' => $totalErrors,
                'success_rate' => $successRate,
            ],
        ];
    }

    /**
     * 日付範囲設定を取得
     */
    private function getDateRangeConfig(string $view): array
    {
        $now = Carbon::now();
        $labels = [];

        switch ($view) {
            case 'hour':
                $startDate = $now->copy()->subHours(23);
                $dateFormat = '%Y-%m-%d %H:00';
                for ($i = 23; $i >= 0; $i--) {
                    $date = $now->copy()->subHours($i);
                    $labels[$date->format('H:00')] = $date->format('Y-m-d H:00');
                }
                break;

            case 'day':
                $startDate = $now->copy()->subDays(6);
                $dateFormat = '%Y-%m-%d';
                for ($i = 6; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $labels[$date->format('n/j')] = $date->format('Y-m-d');
                }
                break;

            case 'week':
                $startDate = $now->copy()->subWeeks(7);
                $dateFormat = '%Y-%u';
                for ($i = 7; $i >= 0; $i--) {
                    $date = $now->copy()->subWeeks($i);
                    $labels[$date->format('n/j') . '週'] = $date->format('Y-W');
                }
                break;

            case 'month':
                $startDate = $now->copy()->subMonths(11);
                $dateFormat = '%Y-%m';
                for ($i = 11; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $labels[$date->format('Y/n')] = $date->format('Y-m');
                }
                break;

            case '3months':
                $startDate = $now->copy()->subMonths(2);
                $dateFormat = '%Y-%m-%d';
                for ($i = 89; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $labels[$date->format('n/j')] = $date->format('Y-m-d');
                }
                break;

            case 'year':
            default:
                $startDate = $now->copy()->subYear();
                $dateFormat = '%Y-%m';
                for ($i = 11; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $labels[$date->format('Y/n')] = $date->format('Y-m');
                }
                break;
        }

        return [$startDate, $dateFormat, $labels];
    }

    /**
     * 同期キュー（ペンディングジョブ）を取得
     */
    public function getSyncQueue(int $page = 1, int $limit = 10, ?int $accountId = null): array
    {
        $query = StripeSyncJob::query()
            ->whereIn('status', [StripeSyncJob::STATUS_PENDING, StripeSyncJob::STATUS_IN_PROGRESS])
            ->with('stripeAccount')
            ->orderBy('created_at', 'desc');

        if ($accountId) {
            $query->where('stripe_account_id', $accountId);
        }

        $total = $query->count();
        $jobs = $query
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'stripe_account_id' => $job->stripe_account_id,
                    'stripe_account_name' => $job->stripeAccount?->display_name ?? 'Unknown',
                    'object_name' => $job->object_name,
                    'job_type' => $job->job_type,
                    'status' => $job->status,
                    'scheduled_at' => $job->scheduled_at?->toIso8601String(),
                    'started_at' => $job->started_at?->toIso8601String(),
                    'created_at' => $job->created_at?->toIso8601String(),
                ];
            });

        return [
            'jobs' => $jobs,
            'pagination' => [
                'total' => $total,
                'per_page' => $limit,
                'current_page' => $page,
                'total_pages' => (int) ceil($total / $limit),
            ],
        ];
    }

    /**
     * ペンディングジョブを削除
     */
    public function deleteQueueJob(int $jobId): bool
    {
        $job = StripeSyncJob::find($jobId);

        if (!$job) {
            return false;
        }

        // ペンディング状態のみ削除可能
        if ($job->status !== StripeSyncJob::STATUS_PENDING) {
            return false;
        }

        $job->delete();
        return true;
    }

    /**
     * 同期履歴を取得
     */
    public function getSyncHistory(
        int $page = 1,
        int $limit = 10,
        ?int $accountId = null,
        ?string $status = null,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        $query = StripeSyncJob::query()
            ->whereIn('status', [
                StripeSyncJob::STATUS_COMPLETED,
                StripeSyncJob::STATUS_FAILED,
                StripeSyncJob::STATUS_CANCELLED,
            ])
            ->with('stripeAccount')
            ->orderBy('finished_at', 'desc');

        if ($accountId) {
            $query->where('stripe_account_id', $accountId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->where('finished_at', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if ($endDate) {
            $query->where('finished_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $total = $query->count();
        $jobs = $query
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get()
            ->map(function ($job) {
                $duration = null;
                if ($job->started_at && $job->finished_at) {
                    $duration = $job->finished_at->diffInSeconds($job->started_at);
                }

                return [
                    'id' => $job->id,
                    'stripe_account_id' => $job->stripe_account_id,
                    'stripe_account_name' => $job->stripeAccount?->display_name ?? 'Unknown',
                    'object_name' => $job->object_name,
                    'job_type' => $job->job_type,
                    'status' => $job->status,
                    'processed_count' => $job->processed_count,
                    'error_count' => $job->error_count,
                    'started_at' => $job->started_at?->toIso8601String(),
                    'finished_at' => $job->finished_at?->toIso8601String(),
                    'duration_seconds' => $duration,
                    'message' => $job->message,
                    'cancelled_by' => $job->cancelled_by,
                    'cancelled_at' => $job->cancelled_at?->toIso8601String(),
                ];
            });

        return [
            'jobs' => $jobs,
            'pagination' => [
                'total' => $total,
                'per_page' => $limit,
                'current_page' => $page,
                'total_pages' => (int) ceil($total / $limit),
            ],
        ];
    }

    /**
     * DB統計を取得（全アカウント合計）
     */
    public function getDbStats(): array
    {
        $stats = [];
        $grandTotal = 0;

        foreach (self::STRIPE_MODELS as $key => $modelClass) {
            $count = $modelClass::count();
            $stats[$key] = $count;
            $grandTotal += $count;
        }

        return [
            'stats' => $stats,
            'grand_total' => $grandTotal,
        ];
    }
}
