<?php

namespace App\Domain\Stripe\Service;

use App\Models\Stripe\StripeAccount;
use App\Models\Stripe\StripeSyncJob;
use App\Models\Stripe\StripeSyncState;
use App\Models\Stripe\StripeSyncError;
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
// Identity/Radar models
use App\Models\Stripe\StripeVerificationSession;
use App\Models\Stripe\StripeVerificationReport;
use App\Models\Stripe\StripeRadarValueList;
use App\Models\Stripe\StripeRadarValueListItem;
// Additional models
use App\Models\Stripe\StripeCountrySpec;
use App\Models\Stripe\StripeMandate;
use App\Models\Stripe\StripeSource;
use App\Models\Stripe\StripeBalance;
use App\Models\Stripe\StripeAccountSession;
use App\Models\Stripe\StripeFinancialConnectionsAccount;
use App\Models\Stripe\StripeFinancialConnectionsSession;
use App\Models\Stripe\StripeTreasuryFinancialAccount;
use App\Models\Stripe\StripeTreasuryTransaction;
use App\Models\Stripe\StripeTerminalConnectionToken;
use App\Models\Stripe\StripeSigmaScheduledQueryRun;
use App\Models\Stripe\StripeReportingReportRun;
use App\Models\Stripe\StripePaymentRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Stripeデータ同期サービス
 * バックフィル、差分同期、Webhook処理を担当
 */
class StripeSyncService
{
    private StripeApiService $apiService;

    /**
     * 同期可能なオブジェクトタイプとその設定
     */
    private const OBJECT_CONFIGS = [
        'customers' => [
            'model' => StripeCustomer::class,
            'fetchMethod' => 'fetchAllCustomers',
            'mapper' => 'mapCustomer',
        ],
        'products' => [
            'model' => StripeProduct::class,
            'fetchMethod' => 'fetchAllProducts',
            'mapper' => 'mapProduct',
        ],
        'prices' => [
            'model' => StripePrice::class,
            'fetchMethod' => 'fetchAllPrices',
            'mapper' => 'mapPrice',
        ],
        'payment_intents' => [
            'model' => StripePaymentIntent::class,
            'fetchMethod' => 'fetchAllPaymentIntents',
            'mapper' => 'mapPaymentIntent',
        ],
        'charges' => [
            'model' => StripeCharge::class,
            'fetchMethod' => 'fetchAllCharges',
            'mapper' => 'mapCharge',
        ],
        'subscriptions' => [
            'model' => StripeSubscription::class,
            'fetchMethod' => 'fetchAllSubscriptions',
            'mapper' => 'mapSubscription',
        ],
        'invoices' => [
            'model' => StripeInvoice::class,
            'fetchMethod' => 'fetchAllInvoices',
            'mapper' => 'mapInvoice',
        ],
        'invoice_items' => [
            'model' => StripeInvoiceItem::class,
            'fetchMethod' => 'fetchAllInvoiceItems',
            'mapper' => 'mapInvoiceItem',
        ],
        'refunds' => [
            'model' => StripeRefund::class,
            'fetchMethod' => 'fetchAllRefunds',
            'mapper' => 'mapRefund',
        ],
        'payment_links' => [
            'model' => StripePaymentLink::class,
            'fetchMethod' => 'fetchAllPaymentLinks',
            'mapper' => 'mapPaymentLink',
        ],
        'checkout_sessions' => [
            'model' => StripeCheckoutSession::class,
            'fetchMethod' => 'fetchAllCheckoutSessions',
            'mapper' => 'mapCheckoutSession',
        ],
        'balance_transactions' => [
            'model' => StripeBalanceTransaction::class,
            'fetchMethod' => 'fetchAllBalanceTransactions',
            'mapper' => 'mapBalanceTransaction',
        ],
        'payouts' => [
            'model' => StripePayout::class,
            'fetchMethod' => 'fetchAllPayouts',
            'mapper' => 'mapPayout',
        ],
        'disputes' => [
            'model' => StripeDispute::class,
            'fetchMethod' => 'fetchAllDisputes',
            'mapper' => 'mapDispute',
        ],
        'credit_notes' => [
            'model' => StripeCreditNote::class,
            'fetchMethod' => 'fetchAllCreditNotes',
            'mapper' => 'mapCreditNote',
        ],
        'events' => [
            'model' => StripeEvent::class,
            'fetchMethod' => 'fetchAllEvents',
            'mapper' => 'mapEvent',
        ],
        // Extended objects
        'coupons' => [
            'model' => StripeCoupon::class,
            'fetchMethod' => 'fetchAllCoupons',
            'mapper' => 'mapCoupon',
        ],
        'promotion_codes' => [
            'model' => StripePromotionCode::class,
            'fetchMethod' => 'fetchAllPromotionCodes',
            'mapper' => 'mapPromotionCode',
        ],
        'setup_intents' => [
            'model' => StripeSetupIntent::class,
            'fetchMethod' => 'fetchAllSetupIntents',
            'mapper' => 'mapSetupIntent',
        ],
        'quotes' => [
            'model' => StripeQuote::class,
            'fetchMethod' => 'fetchAllQuotes',
            'mapper' => 'mapQuote',
        ],
        'subscription_schedules' => [
            'model' => StripeSubscriptionSchedule::class,
            'fetchMethod' => 'fetchAllSubscriptionSchedules',
            'mapper' => 'mapSubscriptionSchedule',
        ],
        'tax_rates' => [
            'model' => StripeTaxRate::class,
            'fetchMethod' => 'fetchAllTaxRates',
            'mapper' => 'mapTaxRate',
        ],
        'tax_codes' => [
            'model' => StripeTaxCode::class,
            'fetchMethod' => 'fetchAllTaxCodes',
            'mapper' => 'mapTaxCode',
        ],
        'shipping_rates' => [
            'model' => StripeShippingRate::class,
            'fetchMethod' => 'fetchAllShippingRates',
            'mapper' => 'mapShippingRate',
        ],
        'files' => [
            'model' => StripeFile::class,
            'fetchMethod' => 'fetchAllFiles',
            'mapper' => 'mapFile',
        ],
        // Issuing objects
        'issuing_cardholders' => [
            'model' => StripeIssuingCardholder::class,
            'fetchMethod' => 'fetchAllIssuingCardholders',
            'mapper' => 'mapIssuingCardholder',
        ],
        'issuing_cards' => [
            'model' => StripeIssuingCard::class,
            'fetchMethod' => 'fetchAllIssuingCards',
            'mapper' => 'mapIssuingCard',
        ],
        'issuing_authorizations' => [
            'model' => StripeIssuingAuthorization::class,
            'fetchMethod' => 'fetchAllIssuingAuthorizations',
            'mapper' => 'mapIssuingAuthorization',
        ],
        'issuing_transactions' => [
            'model' => StripeIssuingTransaction::class,
            'fetchMethod' => 'fetchAllIssuingTransactions',
            'mapper' => 'mapIssuingTransaction',
        ],
        'issuing_disputes' => [
            'model' => StripeIssuingDispute::class,
            'fetchMethod' => 'fetchAllIssuingDisputes',
            'mapper' => 'mapIssuingDispute',
        ],
        // Terminal objects
        'terminal_locations' => [
            'model' => StripeTerminalLocation::class,
            'fetchMethod' => 'fetchAllTerminalLocations',
            'mapper' => 'mapTerminalLocation',
        ],
        'terminal_readers' => [
            'model' => StripeTerminalReader::class,
            'fetchMethod' => 'fetchAllTerminalReaders',
            'mapper' => 'mapTerminalReader',
        ],
        // Identity/Radar objects
        'verification_sessions' => [
            'model' => StripeVerificationSession::class,
            'fetchMethod' => 'fetchAllVerificationSessions',
            'mapper' => 'mapVerificationSession',
        ],
        'radar_value_lists' => [
            'model' => StripeRadarValueList::class,
            'fetchMethod' => 'fetchAllRadarValueLists',
            'mapper' => 'mapRadarValueList',
        ],
        'verification_reports' => [
            'model' => StripeVerificationReport::class,
            'fetchMethod' => 'fetchAllVerificationReports',
            'mapper' => 'mapVerificationReport',
        ],
        // Additional objects
        'account_sessions' => [
            'model' => StripeAccountSession::class,
            'fetchMethod' => 'fetchAllAccountSessions',
            'mapper' => 'mapAccountSession',
        ],
        'financial_connections_accounts' => [
            'model' => StripeFinancialConnectionsAccount::class,
            'fetchMethod' => 'fetchAllFinancialConnectionsAccounts',
            'mapper' => 'mapFinancialConnectionsAccount',
        ],
        'financial_connections_sessions' => [
            'model' => StripeFinancialConnectionsSession::class,
            'fetchMethod' => 'fetchAllFinancialConnectionsSessions',
            'mapper' => 'mapFinancialConnectionsSession',
        ],
        'treasury_financial_accounts' => [
            'model' => StripeTreasuryFinancialAccount::class,
            'fetchMethod' => 'fetchAllTreasuryFinancialAccounts',
            'mapper' => 'mapTreasuryFinancialAccount',
        ],
        'treasury_transactions' => [
            'model' => StripeTreasuryTransaction::class,
            'fetchMethod' => 'fetchAllTreasuryTransactions',
            'mapper' => 'mapTreasuryTransaction',
            'requiresParent' => 'treasury_financial_accounts', // Financial Account IDが必要
        ],
        'terminal_connection_tokens' => [
            'model' => StripeTerminalConnectionToken::class,
            'fetchMethod' => 'fetchAllTerminalConnectionTokens',
            'mapper' => 'mapTerminalConnectionToken',
        ],
        'sigma_scheduled_query_runs' => [
            'model' => StripeSigmaScheduledQueryRun::class,
            'fetchMethod' => 'fetchAllSigmaScheduledQueryRuns',
            'mapper' => 'mapSigmaScheduledQueryRun',
        ],
        'reporting_report_runs' => [
            'model' => StripeReportingReportRun::class,
            'fetchMethod' => 'fetchAllReportingReportRuns',
            'mapper' => 'mapReportingReportRun',
        ],
        'payment_records' => [
            'model' => StripePaymentRecord::class,
            'fetchMethod' => 'fetchAllPaymentRecords',
            'mapper' => 'mapPaymentRecord',
        ],
        // 追加オブジェクト（残り）
        'country_specs' => [
            'model' => StripeCountrySpec::class,
            'fetchMethod' => 'fetchAllCountrySpecs',
            'mapper' => 'mapCountrySpec',
        ],
        'mandates' => [
            'model' => StripeMandate::class,
            'fetchMethod' => 'fetchAllMandates',
            'mapper' => 'mapMandate',
        ],
        'sources' => [
            'model' => StripeSource::class,
            'fetchMethod' => 'fetchAllSources',
            'mapper' => 'mapSource',
        ],
        'radar_value_list_items' => [
            'model' => StripeRadarValueListItem::class,
            'fetchMethod' => 'fetchAllRadarValueListItemsAll',
            'mapper' => 'mapRadarValueListItem',
        ],
    ];

    public function __construct(StripeApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * サポートされているオブジェクトタイプを取得
     */
    public function getSupportedObjectTypes(): array
    {
        return array_keys(self::OBJECT_CONFIGS);
    }

    /**
     * バックフィル実行（全データ取得）
     */
    public function backfill(int $accountId, ?string $objectType = null, string $creator = 'system'): array
    {
        $account = StripeAccount::findOrFail($accountId);
        $this->apiService->initializeForAccount($account);

        $results = [];

        // 特定のオブジェクトタイプが指定された場合
        if ($objectType) {
            // 特殊な同期タイプをチェック
            if ($objectType === 'payment_methods') {
                $results[$objectType] = $this->syncAllPaymentMethods($accountId, $creator);
            } elseif ($objectType === 'subscription_items') {
                $results[$objectType] = $this->syncAllSubscriptionItems($accountId, $creator);
            } elseif ($objectType === 'customer_balance_transactions') {
                $results[$objectType] = $this->syncAllCustomerBalanceTransactions($accountId, $creator);
            } elseif (isset(self::OBJECT_CONFIGS[$objectType])) {
                $results[$objectType] = $this->syncObjectType($account, $objectType, StripeSyncJob::JOB_TYPE_BACKFILL, $creator);
            } else {
                $results[$objectType] = ['error' => 'Unknown object type'];
            }
        } else {
            // 全オブジェクトタイプを同期
            foreach ($this->getSupportedObjectTypes() as $type) {
                $result = $this->syncObjectType($account, $type, StripeSyncJob::JOB_TYPE_BACKFILL, $creator);
                $results[$type] = $result;
            }

            // 特殊な同期タイプも実行
            $results['payment_methods'] = $this->syncAllPaymentMethods($accountId, $creator);
            $results['subscription_items'] = $this->syncAllSubscriptionItems($accountId, $creator);
            $results['customer_balance_transactions'] = $this->syncAllCustomerBalanceTransactions($accountId, $creator);
        }

        // アカウントの最終同期日時を更新
        $account->update([
            'last_synced_at' => now(),
            'last_connected_at' => now(),
        ]);

        return $results;
    }

    /**
     * 差分同期実行
     */
    public function incrementalSync(int $accountId, ?string $objectType = null, string $creator = 'system'): array
    {
        $account = StripeAccount::findOrFail($accountId);
        $this->apiService->initializeForAccount($account);

        $objectTypes = $objectType ? [$objectType] : $this->getSupportedObjectTypes();
        $results = [];

        foreach ($objectTypes as $type) {
            if (!isset(self::OBJECT_CONFIGS[$type])) {
                $results[$type] = ['error' => 'Unknown object type'];
                continue;
            }

            $result = $this->syncObjectType($account, $type, StripeSyncJob::JOB_TYPE_INCREMENTAL, $creator);
            $results[$type] = $result;
        }

        // アカウントの最終同期日時を更新
        $account->update([
            'last_synced_at' => now(),
            'last_connected_at' => now(),
        ]);

        return $results;
    }

    /**
     * 特定のオブジェクトタイプを同期
     */
    private function syncObjectType(StripeAccount $account, string $objectType, string $jobType, string $creator): array
    {
        $config = self::OBJECT_CONFIGS[$objectType];

        // ジョブを作成
        $job = StripeSyncJob::create([
            'stripe_account_id' => $account->id,
            'object_name' => $objectType,
            'status' => StripeSyncJob::STATUS_PENDING,
            'job_type' => $jobType,
            'creator' => $creator,
        ]);

        $job->start();

        try {
            $fetchMethod = $config['fetchMethod'];
            $mapper = $config['mapper'];
            $modelClass = $config['model'];

            // 差分同期の場合、最終同期日時からのデータのみ取得
            $params = [];
            if ($jobType === StripeSyncJob::JOB_TYPE_INCREMENTAL) {
                $syncState = StripeSyncState::where('stripe_account_id', $account->id)
                    ->where('object_name', $objectType)
                    ->first();

                if ($syncState && $syncState->last_synced_at) {
                    $params['created'] = ['gt' => $syncState->last_synced_at->timestamp];
                }
            }

            $processedCount = 0;
            $errorCount = 0;
            $lastSyncedId = null;

            // 親オブジェクトが必要な場合（例: treasury_transactions）
            if (isset($config['requiresParent'])) {
                $results = $this->syncWithParent($account, $objectType, $config, $params, $job, $creator);
                $processedCount = $results['processed'];
                $errorCount = $results['errors'];
                $lastSyncedId = $results['lastId'];
            } else {
                foreach ($this->apiService->$fetchMethod($params) as $stripeObject) {
                    try {
                        $data = $this->$mapper($stripeObject);
                        $modelClass::upsertByStripeId($account->id, $stripeObject->id, $data);
                        $processedCount++;
                        $lastSyncedId = $stripeObject->id;

                        // サブスクリプションの場合、アイテムも同期
                        if ($objectType === 'subscriptions' && isset($stripeObject->items)) {
                            $this->syncSubscriptionItems($account, $stripeObject, $creator);
                        }
                    } catch (\Exception $e) {
                        $errorCount++;
                        $this->logSyncError($account->id, $job->id, $objectType, $stripeObject->id ?? null, $e, $creator);
                    }
                }
            }

            // 同期状態を更新
            StripeSyncState::updateOrCreate(
                ['stripe_account_id' => $account->id, 'object_name' => $objectType],
                [
                    'last_synced_id' => $lastSyncedId,
                    'last_synced_at' => now(),
                    'total_count' => $modelClass::where('stripe_account_id', $account->id)->count(),
                    'updater' => $creator,
                ]
            );

            $job->update([
                'processed_count' => $processedCount,
                'error_count' => $errorCount,
            ]);
            $job->complete("Processed: {$processedCount}, Errors: {$errorCount}");

            return [
                'success' => true,
                'processed_count' => $processedCount,
                'error_count' => $errorCount,
                'job_id' => $job->id,
            ];
        } catch (\Exception $e) {
            $job->fail($e->getMessage());
            $this->logSyncError($account->id, $job->id, $objectType, null, $e, $creator);

            Log::channel('stripe')->error("Sync failed for {$objectType}", [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'job_id' => $job->id,
            ];
        }
    }

    /**
     * サブスクリプションアイテムを同期
     */
    private function syncSubscriptionItems(StripeAccount $account, $subscription, string $creator): void
    {
        if (!isset($subscription->items->data)) {
            return;
        }

        foreach ($subscription->items->data as $item) {
            try {
                $data = $this->mapSubscriptionItem($item, $subscription->id);
                StripeSubscriptionItem::upsertByStripeId($account->id, $item->id, $data);
            } catch (\Exception $e) {
                Log::channel('stripe')->warning("Failed to sync subscription item", [
                    'subscription_id' => $subscription->id,
                    'item_id' => $item->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * 親オブジェクトを必要とするオブジェクトを同期
     * 例: Treasury TransactionsはFinancial Account IDが必要
     */
    private function syncWithParent(
        StripeAccount $account,
        string $objectType,
        array $config,
        array $params,
        StripeSyncJob $job,
        string $creator
    ): array {
        $fetchMethod = $config['fetchMethod'];
        $mapper = $config['mapper'];
        $modelClass = $config['model'];
        $parentType = $config['requiresParent'];

        $processedCount = 0;
        $errorCount = 0;
        $lastSyncedId = null;

        // 親オブジェクトに応じた処理
        if ($parentType === 'treasury_financial_accounts') {
            // Treasury Financial Accountsを取得
            $financialAccounts = StripeTreasuryFinancialAccount::where('stripe_account_id', $account->id)->get();

            foreach ($financialAccounts as $financialAccount) {
                try {
                    foreach ($this->apiService->$fetchMethod($financialAccount->stripe_id, $params) as $stripeObject) {
                        try {
                            $data = $this->$mapper($stripeObject);
                            $modelClass::upsertByStripeId($account->id, $stripeObject->id, $data);
                            $processedCount++;
                            $lastSyncedId = $stripeObject->id;
                        } catch (\Exception $e) {
                            $errorCount++;
                            $this->logSyncError($account->id, $job->id, $objectType, $stripeObject->id ?? null, $e, $creator);
                        }
                    }
                } catch (\Exception $e) {
                    // Financial Accountへのアクセス失敗（権限不足等）
                    Log::channel('stripe')->warning("Failed to sync {$objectType} for financial account", [
                        'financial_account_id' => $financialAccount->stripe_id,
                        'error' => $e->getMessage(),
                    ]);
                    $errorCount++;
                }
            }
        }

        return [
            'processed' => $processedCount,
            'errors' => $errorCount,
            'lastId' => $lastSyncedId,
        ];
    }

    /**
     * エラーをログに記録
     */
    private function logSyncError(
        int $accountId,
        int $jobId,
        string $objectName,
        ?string $stripeObjectId,
        \Exception $e,
        string $creator
    ): void {
        StripeSyncError::create([
            'stripe_account_id' => $accountId,
            'job_id' => $jobId,
            'object_name' => $objectName,
            'stripe_object_id' => $stripeObjectId,
            'error_type' => get_class($e),
            'error_message' => $e->getMessage(),
            'error_context' => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => array_slice($e->getTrace(), 0, 5),
            ],
            'occurred_at' => now(),
            'creator' => $creator,
        ]);
    }

    // ========================================
    // マッパーメソッド
    // ========================================

    private function mapCustomer($obj): array
    {
        return [
            'name' => $obj->name ?? null,
            'email' => $obj->email ?? null,
            'phone' => $obj->phone ?? null,
            'description' => $obj->description ?? null,
            'invoice_prefix' => $obj->invoice_prefix ?? null,
            'default_payment_method_id' => $obj->invoice_settings->default_payment_method ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapProduct($obj): array
    {
        return [
            'name' => $obj->name ?? '',
            'description' => $obj->description ?? null,
            'active' => $obj->active ?? true,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapPrice($obj): array
    {
        return [
            'product_id' => $obj->product ?? null,
            'unit_amount' => $obj->unit_amount ?? null,
            'currency' => $obj->currency ?? '',
            'recurring_interval' => $obj->recurring->interval ?? null,
            'recurring_interval_count' => $obj->recurring->interval_count ?? null,
            'type' => $obj->type ?? '',
            'active' => $obj->active ?? true,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapPaymentIntent($obj): array
    {
        return [
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'customer_id' => $obj->customer ?? null,
            'status' => $obj->status ?? '',
            'description' => $obj->description ?? null,
            'payment_method_types' => $obj->payment_method_types ?? [],
            'capture_method' => $obj->capture_method ?? null,
            'confirmation_method' => $obj->confirmation_method ?? null,
            'payment_method_id' => $obj->payment_method ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapCharge($obj): array
    {
        return [
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'customer_id' => $obj->customer ?? null,
            'invoice_id' => $obj->invoice ?? null,
            'payment_intent_id' => $obj->payment_intent ?? null,
            'description' => $obj->description ?? null,
            'status' => $obj->status ?? '',
            'paid' => $obj->paid ?? false,
            'refunded' => $obj->refunded ?? false,
            'failure_code' => $obj->failure_code ?? null,
            'failure_message' => $obj->failure_message ?? null,
            'captured' => $obj->captured ?? false,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapSubscription($obj): array
    {
        return [
            'customer_id' => $obj->customer ?? '',
            'status' => $obj->status ?? '',
            'current_period_start' => isset($obj->current_period_start) ? Carbon::createFromTimestamp($obj->current_period_start) : null,
            'current_period_end' => isset($obj->current_period_end) ? Carbon::createFromTimestamp($obj->current_period_end) : null,
            'cancel_at_period_end' => $obj->cancel_at_period_end ?? false,
            'canceled_at' => isset($obj->canceled_at) ? Carbon::createFromTimestamp($obj->canceled_at) : null,
            'trial_start' => isset($obj->trial_start) ? Carbon::createFromTimestamp($obj->trial_start) : null,
            'trial_end' => isset($obj->trial_end) ? Carbon::createFromTimestamp($obj->trial_end) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapSubscriptionItem($obj, string $subscriptionId): array
    {
        return [
            'subscription_id' => $subscriptionId,
            'price_id' => $obj->price->id ?? '',
            'quantity' => $obj->quantity ?? 1,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapInvoice($obj): array
    {
        return [
            'customer_id' => $obj->customer ?? '',
            'subscription_id' => $obj->subscription ?? null,
            'status' => $obj->status ?? '',
            'number' => $obj->number ?? null,
            'billing_reason' => $obj->billing_reason ?? null,
            'due_date' => isset($obj->due_date) ? Carbon::createFromTimestamp($obj->due_date) : null,
            'subtotal' => $obj->subtotal ?? 0,
            'tax' => $obj->tax ?? 0,
            'total' => $obj->total ?? 0,
            'currency' => $obj->currency ?? '',
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapInvoiceItem($obj): array
    {
        return [
            'invoice_id' => $obj->invoice ?? null,
            'customer_id' => $obj->customer ?? '',
            'price_id' => $obj->price->id ?? null,
            'quantity' => $obj->quantity ?? 1,
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'description' => $obj->description ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapRefund($obj): array
    {
        return [
            'charge_id' => $obj->charge ?? null,
            'payment_intent_id' => $obj->payment_intent ?? null,
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'status' => $obj->status ?? '',
            'reason' => $obj->reason ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapPaymentLink($obj): array
    {
        return [
            'url' => $obj->url ?? '',
            'active' => $obj->active ?? true,
            'currency' => $obj->currency ?? null,
            'amount_total' => null, // Payment Links don't have amount_total directly
            'description' => null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapCheckoutSession($obj): array
    {
        return [
            'mode' => $obj->mode ?? '',
            'customer_id' => $obj->customer ?? null,
            'payment_intent_id' => $obj->payment_intent ?? null,
            'url' => $obj->url ?? null,
            'status' => $obj->status ?? '',
            'amount_total' => $obj->amount_total ?? null,
            'currency' => $obj->currency ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapBalanceTransaction($obj): array
    {
        return [
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'type' => $obj->type ?? '',
            'net' => $obj->net ?? 0,
            'fee' => $obj->fee ?? 0,
            'source_id' => $obj->source ?? null,
            'description' => $obj->description ?? null,
            'status' => $obj->status ?? '',
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapPayout($obj): array
    {
        return [
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'arrival_date' => isset($obj->arrival_date) ? Carbon::createFromTimestamp($obj->arrival_date) : null,
            'status' => $obj->status ?? '',
            'method' => $obj->method ?? null,
            'type' => $obj->type ?? '',
            'failure_code' => $obj->failure_code ?? null,
            'failure_message' => $obj->failure_message ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapDispute($obj): array
    {
        return [
            'charge_id' => $obj->charge ?? null,
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'status' => $obj->status ?? '',
            'evidence_details' => $obj->evidence_details ?? null,
            'evidence_due_by' => isset($obj->evidence_details->due_by) ? Carbon::createFromTimestamp($obj->evidence_details->due_by) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapCreditNote($obj): array
    {
        return [
            'invoice_id' => $obj->invoice ?? null,
            'customer_id' => $obj->customer ?? null,
            'credit_amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'reason' => $obj->reason ?? null,
            'status' => $obj->status ?? '',
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapEvent($obj): array
    {
        return [
            'type' => $obj->type ?? '',
            'api_version' => $obj->api_version ?? null,
            'data' => isset($obj->data) ? json_decode(json_encode($obj->data), true) : null,
            'request_id' => $obj->request->id ?? null,
            'livemode' => $obj->livemode ?? false,
            'pending_webhooks' => $obj->pending_webhooks ?? 0,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    // ========================================
    // Extended マッパーメソッド
    // ========================================

    private function mapCoupon($obj): array
    {
        return [
            'name' => $obj->name ?? null,
            'percent_off' => $obj->percent_off ?? null,
            'amount_off' => $obj->amount_off ?? null,
            'currency' => $obj->currency ?? null,
            'duration' => $obj->duration ?? '',
            'duration_in_months' => $obj->duration_in_months ?? null,
            'max_redemptions' => $obj->max_redemptions ?? null,
            'times_redeemed' => $obj->times_redeemed ?? 0,
            'redeem_by' => isset($obj->redeem_by) ? Carbon::createFromTimestamp($obj->redeem_by) : null,
            'valid' => $obj->valid ?? false,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapPromotionCode($obj): array
    {
        return [
            'code' => $obj->code ?? '',
            'coupon_id' => $obj->coupon->id ?? null,
            'customer_id' => $obj->customer ?? null,
            'active' => $obj->active ?? true,
            'max_redemptions' => $obj->max_redemptions ?? null,
            'times_redeemed' => $obj->times_redeemed ?? 0,
            'expires_at' => isset($obj->expires_at) ? Carbon::createFromTimestamp($obj->expires_at) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapSetupIntent($obj): array
    {
        return [
            'customer_id' => $obj->customer ?? null,
            'payment_method_id' => $obj->payment_method ?? null,
            'status' => $obj->status ?? '',
            'usage' => $obj->usage ?? null,
            'description' => $obj->description ?? null,
            'payment_method_types' => $obj->payment_method_types ?? [],
            'cancellation_reason' => $obj->cancellation_reason ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapQuote($obj): array
    {
        return [
            'customer_id' => $obj->customer ?? null,
            'status' => $obj->status ?? '',
            'amount_subtotal' => $obj->amount_subtotal ?? 0,
            'amount_total' => $obj->amount_total ?? 0,
            'currency' => $obj->currency ?? '',
            'description' => $obj->description ?? null,
            'expires_at' => isset($obj->expires_at) ? Carbon::createFromTimestamp($obj->expires_at) : null,
            'invoice_id' => $obj->invoice ?? null,
            'subscription_id' => $obj->subscription ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapSubscriptionSchedule($obj): array
    {
        return [
            'customer_id' => $obj->customer ?? null,
            'subscription_id' => $obj->subscription ?? null,
            'status' => $obj->status ?? '',
            'end_behavior' => $obj->end_behavior ?? null,
            'current_phase' => isset($obj->current_phase) ? json_decode(json_encode($obj->current_phase), true) : null,
            'phases' => isset($obj->phases) ? json_decode(json_encode($obj->phases), true) : [],
            'released_at' => isset($obj->released_at) ? Carbon::createFromTimestamp($obj->released_at) : null,
            'released_subscription_id' => $obj->released_subscription ?? null,
            'canceled_at' => isset($obj->canceled_at) ? Carbon::createFromTimestamp($obj->canceled_at) : null,
            'completed_at' => isset($obj->completed_at) ? Carbon::createFromTimestamp($obj->completed_at) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapTaxRate($obj): array
    {
        return [
            'display_name' => $obj->display_name ?? '',
            'description' => $obj->description ?? null,
            'percentage' => $obj->percentage ?? 0,
            'inclusive' => $obj->inclusive ?? false,
            'active' => $obj->active ?? true,
            'country' => $obj->country ?? null,
            'state' => $obj->state ?? null,
            'jurisdiction' => $obj->jurisdiction ?? null,
            'tax_type' => $obj->tax_type ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapTaxCode($obj): array
    {
        return [
            'name' => $obj->name ?? '',
            'description' => $obj->description ?? null,
        ];
    }

    private function mapShippingRate($obj): array
    {
        return [
            'display_name' => $obj->display_name ?? '',
            'type' => $obj->type ?? '',
            'fixed_amount' => $obj->fixed_amount->amount ?? null,
            'currency' => $obj->fixed_amount->currency ?? null,
            'active' => $obj->active ?? true,
            'delivery_estimate' => isset($obj->delivery_estimate) ? json_decode(json_encode($obj->delivery_estimate), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapFile($obj): array
    {
        return [
            'filename' => $obj->filename ?? null,
            'purpose' => $obj->purpose ?? '',
            'type' => $obj->type ?? null,
            'size' => $obj->size ?? 0,
            'url' => $obj->url ?? null,
            'expires_at' => isset($obj->expires_at) ? Carbon::createFromTimestamp($obj->expires_at) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    // ========================================
    // Issuing マッパーメソッド
    // ========================================

    private function mapIssuingCardholder($obj): array
    {
        return [
            'name' => $obj->name ?? '',
            'email' => $obj->email ?? null,
            'phone_number' => $obj->phone_number ?? null,
            'status' => $obj->status ?? '',
            'type' => $obj->type ?? '',
            'billing' => isset($obj->billing) ? json_decode(json_encode($obj->billing), true) : null,
            'company' => isset($obj->company) ? json_decode(json_encode($obj->company), true) : null,
            'individual' => isset($obj->individual) ? json_decode(json_encode($obj->individual), true) : null,
            'spending_controls' => isset($obj->spending_controls) ? json_decode(json_encode($obj->spending_controls), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapIssuingCard($obj): array
    {
        return [
            'cardholder_id' => $obj->cardholder->id ?? null,
            'last4' => $obj->last4 ?? '',
            'exp_month' => $obj->exp_month ?? null,
            'exp_year' => $obj->exp_year ?? null,
            'brand' => $obj->brand ?? '',
            'type' => $obj->type ?? '',
            'status' => $obj->status ?? '',
            'currency' => $obj->currency ?? '',
            'cancellation_reason' => $obj->cancellation_reason ?? null,
            'spending_controls' => isset($obj->spending_controls) ? json_decode(json_encode($obj->spending_controls), true) : null,
            'replaced_by_id' => $obj->replaced_by ?? null,
            'replacement_for_id' => $obj->replacement_for ?? null,
            'replacement_reason' => $obj->replacement_reason ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapIssuingAuthorization($obj): array
    {
        return [
            'card_id' => $obj->card->id ?? null,
            'cardholder_id' => $obj->cardholder ?? null,
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'status' => $obj->status ?? '',
            'approved' => $obj->approved ?? false,
            'authorization_method' => $obj->authorization_method ?? null,
            'merchant_data' => isset($obj->merchant_data) ? json_decode(json_encode($obj->merchant_data), true) : null,
            'pending_request' => isset($obj->pending_request) ? json_decode(json_encode($obj->pending_request), true) : null,
            'request_history' => isset($obj->request_history) ? json_decode(json_encode($obj->request_history), true) : [],
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapIssuingTransaction($obj): array
    {
        return [
            'card_id' => $obj->card ?? null,
            'cardholder_id' => $obj->cardholder ?? null,
            'authorization_id' => $obj->authorization ?? null,
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'type' => $obj->type ?? '',
            'merchant_amount' => $obj->merchant_amount ?? 0,
            'merchant_currency' => $obj->merchant_currency ?? null,
            'merchant_data' => isset($obj->merchant_data) ? json_decode(json_encode($obj->merchant_data), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapIssuingDispute($obj): array
    {
        return [
            'transaction_id' => $obj->transaction ?? null,
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'status' => $obj->status ?? '',
            'reason' => $obj->reason ?? null,
            'evidence' => isset($obj->evidence) ? json_decode(json_encode($obj->evidence), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    // ========================================
    // Terminal マッパーメソッド
    // ========================================

    private function mapTerminalLocation($obj): array
    {
        return [
            'display_name' => $obj->display_name ?? '',
            'address' => isset($obj->address) ? json_decode(json_encode($obj->address), true) : null,
            'configuration_overrides' => $obj->configuration_overrides ?? null,
            'livemode' => $obj->livemode ?? false,
        ];
    }

    private function mapTerminalReader($obj): array
    {
        return [
            'location_id' => $obj->location ?? null,
            'label' => $obj->label ?? null,
            'device_type' => $obj->device_type ?? '',
            'serial_number' => $obj->serial_number ?? null,
            'status' => $obj->status ?? null,
            'device_sw_version' => $obj->device_sw_version ?? null,
            'ip_address' => $obj->ip_address ?? null,
            'livemode' => $obj->livemode ?? false,
        ];
    }

    // ========================================
    // Identity/Radar マッパーメソッド
    // ========================================

    private function mapVerificationSession($obj): array
    {
        return [
            'status' => $obj->status ?? '',
            'type' => $obj->type ?? '',
            'client_secret' => $obj->client_secret ?? null,
            'last_error' => isset($obj->last_error) ? json_decode(json_encode($obj->last_error), true) : null,
            'last_verification_report_id' => $obj->last_verification_report ?? null,
            'verified_outputs' => isset($obj->verified_outputs) ? json_decode(json_encode($obj->verified_outputs), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapRadarValueList($obj): array
    {
        return [
            'alias' => $obj->alias ?? '',
            'name' => $obj->name ?? '',
            'item_type' => $obj->item_type ?? '',
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    // ========================================
    // 追加オブジェクト マッパーメソッド
    // ========================================

    private function mapVerificationReport($obj): array
    {
        return [
            'verification_session_id' => $obj->verification_session ?? null,
            'type' => $obj->type ?? '',
            'document' => isset($obj->document) ? json_decode(json_encode($obj->document), true) : null,
            'selfie' => isset($obj->selfie) ? json_decode(json_encode($obj->selfie), true) : null,
            'id_number' => isset($obj->id_number) ? json_decode(json_encode($obj->id_number), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapAccountSession($obj): array
    {
        return [
            'account_id' => $obj->account ?? null,
            'client_secret' => $obj->client_secret ?? null,
            'components' => isset($obj->components) ? json_decode(json_encode($obj->components), true) : null,
            'expires_at' => isset($obj->expires_at) ? Carbon::createFromTimestamp($obj->expires_at) : null,
            'livemode' => $obj->livemode ?? false,
        ];
    }

    private function mapFinancialConnectionsAccount($obj): array
    {
        return [
            'account_holder' => isset($obj->account_holder) ? json_decode(json_encode($obj->account_holder), true) : null,
            'balance' => isset($obj->balance) ? json_decode(json_encode($obj->balance), true) : null,
            'balance_refresh' => isset($obj->balance_refresh) ? json_decode(json_encode($obj->balance_refresh), true) : null,
            'category' => $obj->category ?? '',
            'display_name' => $obj->display_name ?? null,
            'institution_name' => $obj->institution_name ?? null,
            'last4' => $obj->last4 ?? null,
            'ownership' => isset($obj->ownership) ? json_decode(json_encode($obj->ownership), true) : null,
            'ownership_refresh' => isset($obj->ownership_refresh) ? json_decode(json_encode($obj->ownership_refresh), true) : null,
            'permissions' => isset($obj->permissions) ? json_decode(json_encode($obj->permissions), true) : null,
            'status' => $obj->status ?? '',
            'subcategory' => $obj->subcategory ?? null,
            'supported_payment_method_types' => isset($obj->supported_payment_method_types) ? json_decode(json_encode($obj->supported_payment_method_types), true) : null,
            'transaction_refresh' => isset($obj->transaction_refresh) ? json_decode(json_encode($obj->transaction_refresh), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapFinancialConnectionsSession($obj): array
    {
        return [
            'account_holder' => isset($obj->account_holder) ? json_decode(json_encode($obj->account_holder), true) : null,
            'accounts' => isset($obj->accounts) ? json_decode(json_encode($obj->accounts), true) : null,
            'client_secret' => $obj->client_secret ?? null,
            'filters' => isset($obj->filters) ? json_decode(json_encode($obj->filters), true) : null,
            'permissions' => isset($obj->permissions) ? json_decode(json_encode($obj->permissions), true) : null,
            'return_url' => $obj->return_url ?? null,
            'livemode' => $obj->livemode ?? false,
        ];
    }

    private function mapTreasuryFinancialAccount($obj): array
    {
        return [
            'active_features' => isset($obj->active_features) ? json_decode(json_encode($obj->active_features), true) : null,
            'balance' => isset($obj->balance) ? json_decode(json_encode($obj->balance), true) : null,
            'country' => $obj->country ?? '',
            'financial_addresses' => isset($obj->financial_addresses) ? json_decode(json_encode($obj->financial_addresses), true) : null,
            'pending_features' => isset($obj->pending_features) ? json_decode(json_encode($obj->pending_features), true) : null,
            'platform_restrictions' => isset($obj->platform_restrictions) ? json_decode(json_encode($obj->platform_restrictions), true) : null,
            'restricted_features' => isset($obj->restricted_features) ? json_decode(json_encode($obj->restricted_features), true) : null,
            'status' => $obj->status ?? '',
            'status_details' => isset($obj->status_details) ? json_decode(json_encode($obj->status_details), true) : null,
            'supported_currencies' => isset($obj->supported_currencies) ? json_decode(json_encode($obj->supported_currencies), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapTreasuryTransaction($obj): array
    {
        return [
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'description' => $obj->description ?? null,
            'entries' => isset($obj->entries) ? json_decode(json_encode($obj->entries), true) : null,
            'financial_account' => $obj->financial_account ?? null,
            'flow' => $obj->flow ?? null,
            'flow_details' => isset($obj->flow_details) ? json_decode(json_encode($obj->flow_details), true) : null,
            'flow_type' => $obj->flow_type ?? null,
            'status' => $obj->status ?? '',
            'status_transitions' => isset($obj->status_transitions) ? json_decode(json_encode($obj->status_transitions), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapTerminalConnectionToken($obj): array
    {
        return [
            'secret' => $obj->secret ?? null,
            'location' => $obj->location ?? null,
        ];
    }

    private function mapSigmaScheduledQueryRun($obj): array
    {
        return [
            'title' => $obj->title ?? '',
            'sql' => $obj->sql ?? null,
            'status' => $obj->status ?? '',
            'data_load_time' => isset($obj->data_load_time) ? Carbon::createFromTimestamp($obj->data_load_time) : null,
            'result_available_until' => isset($obj->result_available_until) ? Carbon::createFromTimestamp($obj->result_available_until) : null,
            'file_id' => isset($obj->file) ? $obj->file->id ?? $obj->file : null,
            'error' => isset($obj->error) ? json_decode(json_encode($obj->error), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapReportingReportRun($obj): array
    {
        return [
            'report_type' => $obj->report_type ?? '',
            'parameters' => isset($obj->parameters) ? json_decode(json_encode($obj->parameters), true) : null,
            'status' => $obj->status ?? '',
            'succeeded_at' => isset($obj->succeeded_at) ? Carbon::createFromTimestamp($obj->succeeded_at) : null,
            'result_id' => isset($obj->result) ? $obj->result->id ?? $obj->result : null,
            'error' => $obj->error ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapPaymentRecord($obj): array
    {
        return [
            'amount_canceled' => $obj->amount_canceled ?? 0,
            'amount_failed' => $obj->amount_failed ?? 0,
            'amount_guaranteed' => $obj->amount_guaranteed ?? 0,
            'amount_requested' => $obj->amount_requested ?? 0,
            'customer_details' => isset($obj->customer_details) ? json_decode(json_encode($obj->customer_details), true) : null,
            'customer_presence' => $obj->customer_presence ?? null,
            'payment_method_types' => isset($obj->payment_method_types) ? json_decode(json_encode($obj->payment_method_types), true) : null,
            'payment_reference' => $obj->payment_reference ?? null,
            'shipping_details' => isset($obj->shipping_details) ? json_decode(json_encode($obj->shipping_details), true) : null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    // ========================================
    // 追加オブジェクト マッパーメソッド（残り）
    // ========================================

    private function mapCountrySpec($obj): array
    {
        return [
            'default_currency' => $obj->default_currency ?? '',
            'supported_bank_account_currencies' => isset($obj->supported_bank_account_currencies) ? json_decode(json_encode($obj->supported_bank_account_currencies), true) : null,
            'supported_payment_currencies' => isset($obj->supported_payment_currencies) ? json_decode(json_encode($obj->supported_payment_currencies), true) : null,
            'supported_payment_methods' => isset($obj->supported_payment_methods) ? json_decode(json_encode($obj->supported_payment_methods), true) : null,
            'supported_transfer_countries' => isset($obj->supported_transfer_countries) ? json_decode(json_encode($obj->supported_transfer_countries), true) : null,
            'verification_fields' => isset($obj->verification_fields) ? json_decode(json_encode($obj->verification_fields), true) : null,
        ];
    }

    private function mapMandate($obj): array
    {
        return [
            'customer_acceptance' => isset($obj->customer_acceptance) ? json_decode(json_encode($obj->customer_acceptance), true) : null,
            'payment_method_id' => $obj->payment_method ?? null,
            'payment_method_details' => isset($obj->payment_method_details) ? json_decode(json_encode($obj->payment_method_details), true) : null,
            'single_use' => isset($obj->single_use) ? json_decode(json_encode($obj->single_use), true) : null,
            'status' => $obj->status ?? '',
            'type' => $obj->type ?? '',
            'multi_use' => isset($obj->multi_use) ? json_decode(json_encode($obj->multi_use), true) : null,
            'livemode' => $obj->livemode ?? false,
        ];
    }

    private function mapSource($obj): array
    {
        return [
            'customer_id' => $obj->customer ?? null,
            'amount' => $obj->amount ?? null,
            'currency' => $obj->currency ?? null,
            'flow' => $obj->flow ?? null,
            'owner' => isset($obj->owner) ? json_decode(json_encode($obj->owner), true) : null,
            'receiver' => isset($obj->receiver) ? json_decode(json_encode($obj->receiver), true) : null,
            'redirect' => isset($obj->redirect) ? json_decode(json_encode($obj->redirect), true) : null,
            'source_order' => isset($obj->source_order) ? json_decode(json_encode($obj->source_order), true) : null,
            'statement_descriptor' => $obj->statement_descriptor ?? null,
            'status' => $obj->status ?? '',
            'type' => $obj->type ?? '',
            'usage' => $obj->usage ?? null,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    private function mapRadarValueListItem($obj): array
    {
        return [
            'value_list_id' => $obj->value_list ?? null,
            'value' => $obj->value ?? '',
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    /**
     * 同期ジョブの状態を取得
     */
    public function getJobStatus(int $jobId): ?StripeSyncJob
    {
        return StripeSyncJob::find($jobId);
    }

    /**
     * アカウントの同期状態を取得
     */
    public function getSyncStates(int $accountId): array
    {
        return StripeSyncState::where('stripe_account_id', $accountId)
            ->get()
            ->keyBy('object_name')
            ->toArray();
    }

    /**
     * 未解決のエラーを取得
     */
    public function getUnresolvedErrors(int $accountId, ?string $objectType = null): array
    {
        $query = StripeSyncError::where('stripe_account_id', $accountId)
            ->where('resolved_flag', false);

        if ($objectType) {
            $query->where('object_name', $objectType);
        }

        return $query->orderBy('occurred_at', 'desc')->get()->toArray();
    }

    // ========================================
    // ジョブ管理メソッド
    // ========================================

    /**
     * 同期ジョブ一覧を取得
     */
    public function getJobs(int $accountId, array $filters = []): array
    {
        $query = StripeSyncJob::where('stripe_account_id', $accountId);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['object_name'])) {
            $query->where('object_name', $filters['object_name']);
        }

        if (isset($filters['job_type'])) {
            $query->where('job_type', $filters['job_type']);
        }

        $limit = $filters['limit'] ?? 50;
        $page = $filters['page'] ?? 1;

        return [
            'jobs' => $query->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get()
                ->toArray(),
            'total' => $query->count(),
            'page' => $page,
            'limit' => $limit,
        ];
    }

    /**
     * 進行中のジョブを取得
     */
    public function getRunningJobs(int $accountId): array
    {
        return StripeSyncJob::where('stripe_account_id', $accountId)
            ->where('status', StripeSyncJob::STATUS_IN_PROGRESS)
            ->orderBy('started_at', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * ジョブをキャンセル
     */
    public function cancelJob(int $jobId, string $updater = 'system'): bool
    {
        $job = StripeSyncJob::find($jobId);

        if (!$job) {
            return false;
        }

        // 進行中またはペンディングのジョブのみキャンセル可能
        if (!in_array($job->status, [StripeSyncJob::STATUS_PENDING, StripeSyncJob::STATUS_IN_PROGRESS])) {
            return false;
        }

        $job->update([
            'status' => StripeSyncJob::STATUS_CANCELLED,
            'finished_at' => now(),
            'message' => 'Cancelled by user',
            'updater' => $updater,
        ]);

        return true;
    }

    /**
     * エラーを解決済みにする
     */
    public function resolveError(int $errorId, string $updater = 'system'): bool
    {
        $error = StripeSyncError::find($errorId);

        if (!$error) {
            return false;
        }

        $error->update([
            'resolved_flag' => true,
            'resolved_at' => now(),
            'updater' => $updater,
        ]);

        return true;
    }

    /**
     * 複数のエラーを解決済みにする
     */
    public function resolveErrors(array $errorIds, string $updater = 'system'): int
    {
        return StripeSyncError::whereIn('id', $errorIds)
            ->where('resolved_flag', false)
            ->update([
                'resolved_flag' => true,
                'resolved_at' => now(),
                'updater' => $updater,
            ]);
    }

    /**
     * 全エラーを解決済みにする
     */
    public function resolveAllErrors(int $accountId, ?string $objectType = null, string $updater = 'system'): int
    {
        $query = StripeSyncError::where('stripe_account_id', $accountId)
            ->where('resolved_flag', false);

        if ($objectType) {
            $query->where('object_name', $objectType);
        }

        return $query->update([
            'resolved_flag' => true,
            'resolved_at' => now(),
            'updater' => $updater,
        ]);
    }

    // ========================================
    // 特殊な同期メソッド
    // ========================================

    /**
     * 顧客の支払い方法を同期
     */
    public function syncPaymentMethodsForCustomer(int $accountId, string $customerId, string $creator = 'system'): array
    {
        $account = StripeAccount::findOrFail($accountId);
        $this->apiService->initializeForAccount($account);

        $processedCount = 0;
        $errorCount = 0;

        try {
            foreach ($this->apiService->fetchAllPaymentMethods($customerId) as $paymentMethod) {
                try {
                    $data = $this->mapPaymentMethod($paymentMethod);
                    StripePaymentMethod::upsertByStripeId($accountId, $paymentMethod->id, $data);
                    $processedCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    Log::channel('stripe')->warning("Failed to sync payment method", [
                        'customer_id' => $customerId,
                        'payment_method_id' => $paymentMethod->id ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return [
                'success' => true,
                'processed_count' => $processedCount,
                'error_count' => $errorCount,
            ];
        } catch (\Exception $e) {
            Log::channel('stripe')->error("Failed to sync payment methods for customer", [
                'account_id' => $accountId,
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 全顧客の支払い方法を同期
     */
    public function syncAllPaymentMethods(int $accountId, string $creator = 'system'): array
    {
        $account = StripeAccount::findOrFail($accountId);
        $this->apiService->initializeForAccount($account);

        $job = StripeSyncJob::create([
            'stripe_account_id' => $accountId,
            'object_name' => 'payment_methods',
            'status' => StripeSyncJob::STATUS_PENDING,
            'job_type' => StripeSyncJob::JOB_TYPE_BACKFILL,
            'creator' => $creator,
        ]);

        $job->start();

        try {
            $totalProcessed = 0;
            $totalErrors = 0;

            // 全顧客を取得して、各顧客の支払い方法を同期
            $customers = StripeCustomer::where('stripe_account_id', $accountId)->get();

            foreach ($customers as $customer) {
                $result = $this->syncPaymentMethodsForCustomer($accountId, $customer->stripe_id, $creator);
                $totalProcessed += $result['processed_count'] ?? 0;
                $totalErrors += $result['error_count'] ?? 0;
            }

            // 同期状態を更新
            StripeSyncState::updateOrCreate(
                ['stripe_account_id' => $accountId, 'object_name' => 'payment_methods'],
                [
                    'last_synced_at' => now(),
                    'total_count' => StripePaymentMethod::where('stripe_account_id', $accountId)->count(),
                    'updater' => $creator,
                ]
            );

            $job->update([
                'processed_count' => $totalProcessed,
                'error_count' => $totalErrors,
            ]);
            $job->complete("Processed: {$totalProcessed}, Errors: {$totalErrors}");

            return [
                'success' => true,
                'processed_count' => $totalProcessed,
                'error_count' => $totalErrors,
                'job_id' => $job->id,
            ];
        } catch (\Exception $e) {
            $job->fail($e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'job_id' => $job->id,
            ];
        }
    }

    /**
     * サブスクリプションアイテムを単独で同期（全サブスクリプション）
     */
    public function syncAllSubscriptionItems(int $accountId, string $creator = 'system'): array
    {
        $account = StripeAccount::findOrFail($accountId);
        $this->apiService->initializeForAccount($account);

        $job = StripeSyncJob::create([
            'stripe_account_id' => $accountId,
            'object_name' => 'subscription_items',
            'status' => StripeSyncJob::STATUS_PENDING,
            'job_type' => StripeSyncJob::JOB_TYPE_BACKFILL,
            'creator' => $creator,
        ]);

        $job->start();

        try {
            $totalProcessed = 0;
            $totalErrors = 0;

            // 全サブスクリプションを取得して、各サブスクリプションのアイテムを同期
            foreach ($this->apiService->fetchAllSubscriptions(['status' => 'all', 'expand' => ['data.items']]) as $subscription) {
                if (isset($subscription->items->data)) {
                    foreach ($subscription->items->data as $item) {
                        try {
                            $data = $this->mapSubscriptionItem($item, $subscription->id);
                            StripeSubscriptionItem::upsertByStripeId($accountId, $item->id, $data);
                            $totalProcessed++;
                        } catch (\Exception $e) {
                            $totalErrors++;
                            Log::channel('stripe')->warning("Failed to sync subscription item", [
                                'subscription_id' => $subscription->id,
                                'item_id' => $item->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            }

            // 同期状態を更新
            StripeSyncState::updateOrCreate(
                ['stripe_account_id' => $accountId, 'object_name' => 'subscription_items'],
                [
                    'last_synced_at' => now(),
                    'total_count' => StripeSubscriptionItem::where('stripe_account_id', $accountId)->count(),
                    'updater' => $creator,
                ]
            );

            $job->update([
                'processed_count' => $totalProcessed,
                'error_count' => $totalErrors,
            ]);
            $job->complete("Processed: {$totalProcessed}, Errors: {$totalErrors}");

            return [
                'success' => true,
                'processed_count' => $totalProcessed,
                'error_count' => $totalErrors,
                'job_id' => $job->id,
            ];
        } catch (\Exception $e) {
            $job->fail($e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'job_id' => $job->id,
            ];
        }
    }

    /**
     * 支払い方法のマッパー
     */
    private function mapPaymentMethod($obj): array
    {
        return [
            'customer_id' => $obj->customer ?? null,
            'type' => $obj->type ?? '',
            'card_brand' => $obj->card->brand ?? null,
            'card_last4' => $obj->card->last4 ?? null,
            'card_exp_month' => $obj->card->exp_month ?? null,
            'card_exp_year' => $obj->card->exp_year ?? null,
            'billing_details' => [
                'name' => $obj->billing_details->name ?? null,
                'email' => $obj->billing_details->email ?? null,
                'phone' => $obj->billing_details->phone ?? null,
            ],
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    /**
     * 顧客残高取引を同期（特定の顧客）
     */
    public function syncCustomerBalanceTransactionsForCustomer(int $accountId, string $customerId, string $creator = 'system'): array
    {
        $account = StripeAccount::findOrFail($accountId);
        $this->apiService->initializeForAccount($account);

        $processedCount = 0;
        $errorCount = 0;

        try {
            foreach ($this->apiService->fetchCustomerBalanceTransactions($customerId) as $transaction) {
                try {
                    $data = $this->mapCustomerBalanceTransaction($transaction, $customerId);
                    StripeCustomerBalanceTransaction::upsertByStripeId($accountId, $transaction->id, $data);
                    $processedCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    Log::channel('stripe')->warning("Failed to sync customer balance transaction", [
                        'customer_id' => $customerId,
                        'transaction_id' => $transaction->id ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return [
                'success' => true,
                'processed_count' => $processedCount,
                'error_count' => $errorCount,
            ];
        } catch (\Exception $e) {
            Log::channel('stripe')->error("Failed to sync customer balance transactions", [
                'account_id' => $accountId,
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 全顧客の残高取引を同期
     */
    public function syncAllCustomerBalanceTransactions(int $accountId, string $creator = 'system'): array
    {
        $account = StripeAccount::findOrFail($accountId);
        $this->apiService->initializeForAccount($account);

        $job = StripeSyncJob::create([
            'stripe_account_id' => $accountId,
            'object_name' => 'customer_balance_transactions',
            'status' => StripeSyncJob::STATUS_PENDING,
            'job_type' => StripeSyncJob::JOB_TYPE_BACKFILL,
            'creator' => $creator,
        ]);

        $job->start();

        try {
            $totalProcessed = 0;
            $totalErrors = 0;

            // 全顧客を取得して、各顧客の残高取引を同期
            $customers = StripeCustomer::where('stripe_account_id', $accountId)->get();

            foreach ($customers as $customer) {
                $result = $this->syncCustomerBalanceTransactionsForCustomer($accountId, $customer->stripe_id, $creator);
                $totalProcessed += $result['processed_count'] ?? 0;
                $totalErrors += $result['error_count'] ?? 0;
            }

            // 同期状態を更新
            StripeSyncState::updateOrCreate(
                ['stripe_account_id' => $accountId, 'object_name' => 'customer_balance_transactions'],
                [
                    'last_synced_at' => now(),
                    'total_count' => StripeCustomerBalanceTransaction::where('stripe_account_id', $accountId)->count(),
                    'updater' => $creator,
                ]
            );

            $job->update([
                'processed_count' => $totalProcessed,
                'error_count' => $totalErrors,
            ]);
            $job->complete("Processed: {$totalProcessed}, Errors: {$totalErrors}");

            return [
                'success' => true,
                'processed_count' => $totalProcessed,
                'error_count' => $totalErrors,
                'job_id' => $job->id,
            ];
        } catch (\Exception $e) {
            $job->fail($e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'job_id' => $job->id,
            ];
        }
    }

    /**
     * 顧客残高取引のマッパー
     */
    private function mapCustomerBalanceTransaction($obj, string $customerId): array
    {
        return [
            'customer_id' => $customerId,
            'type' => $obj->type ?? '',
            'amount' => $obj->amount ?? 0,
            'currency' => $obj->currency ?? '',
            'ending_balance' => $obj->ending_balance ?? 0,
            'livemode' => $obj->livemode ?? false,
            'stripe_created' => isset($obj->created) ? Carbon::createFromTimestamp($obj->created) : null,
        ];
    }

    /**
     * サポートされているオブジェクトタイプを拡張版で取得
     */
    public function getAllSupportedObjectTypes(): array
    {
        return array_merge(
            $this->getSupportedObjectTypes(),
            ['payment_methods', 'subscription_items', 'customer_balance_transactions']
        );
    }
}
