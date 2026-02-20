<?php

namespace App\Domain\Stripe\Service;

use App\Models\Stripe\StripeAccount;
use Stripe\StripeClient;
use Stripe\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Stripe API のラッパーサービス
 * APIとの通信を担当
 */
class StripeApiService
{
    private ?StripeClient $client = null;
    private ?StripeAccount $account = null;

    /**
     * 指定されたアカウントでクライアントを初期化
     */
    public function initializeForAccount(StripeAccount $account): self
    {
        if (!$account->secret_key) {
            throw new \Exception(__('stripe_account.dashboard_stats.errors.api_key_not_configured'));
        }

        $this->account = $account;
        $this->client = new StripeClient($account->secret_key);

        return $this;
    }

    /**
     * Stripeクライアントを取得
     */
    public function getClient(): StripeClient
    {
        if (!$this->client) {
            throw new \Exception('Stripe client not initialized. Call initializeForAccount() first.');
        }
        return $this->client;
    }

    /**
     * 現在のアカウントを取得
     */
    public function getAccount(): ?StripeAccount
    {
        return $this->account;
    }

    /**
     * ページネーションを使用して全データを取得するジェネレータ
     *
     * @param callable $listMethod リスト取得メソッド
     * @param array $baseParams 基本パラメータ
     * @param int $limit 1回のリクエストあたりの件数
     * @return \Generator
     */
    public function fetchAllWithPagination(callable $listMethod, array $baseParams = [], int $limit = 100): \Generator
    {
        $hasMore = true;
        $startingAfter = null;

        while ($hasMore) {
            $params = array_merge($baseParams, ['limit' => $limit]);

            if ($startingAfter) {
                $params['starting_after'] = $startingAfter;
            }

            $response = $listMethod($params);
            $items = $response->data ?? [];

            foreach ($items as $item) {
                yield $item;
            }

            $hasMore = $response->has_more ?? false;

            if ($hasMore && !empty($items)) {
                $startingAfter = end($items)->id;
            } else {
                $hasMore = false;
            }
        }
    }

    /**
     * 顧客を全件取得
     */
    public function fetchAllCustomers(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->customers->all($p),
            $params
        );
    }

    /**
     * 商品を全件取得
     * Note: activeパラメータを指定しない場合、全ての商品（active/inactive両方）を取得
     */
    public function fetchAllProducts(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->products->all($p),
            $params
        );
    }

    /**
     * 価格を全件取得
     * Note: activeパラメータを指定しない場合、全ての価格（active/inactive両方）を取得
     */
    public function fetchAllPrices(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->prices->all($p),
            $params
        );
    }

    /**
     * 支払いインテントを全件取得
     */
    public function fetchAllPaymentIntents(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->paymentIntents->all($p),
            $params
        );
    }

    /**
     * 請求を全件取得
     */
    public function fetchAllCharges(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->charges->all($p),
            $params
        );
    }

    /**
     * サブスクリプションを全件取得
     */
    public function fetchAllSubscriptions(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->subscriptions->all($p),
            array_merge(['status' => 'all'], $params)
        );
    }

    /**
     * 請求書を全件取得
     */
    public function fetchAllInvoices(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->invoices->all($p),
            $params
        );
    }

    /**
     * 請求書アイテムを全件取得
     */
    public function fetchAllInvoiceItems(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->invoiceItems->all($p),
            $params
        );
    }

    /**
     * 返金を全件取得
     */
    public function fetchAllRefunds(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->refunds->all($p),
            $params
        );
    }

    /**
     * 支払い方法を全件取得
     */
    public function fetchAllPaymentMethods(string $customerId, array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->paymentMethods->all($p),
            array_merge(['customer' => $customerId], $params)
        );
    }

    /**
     * ペイメントリンクを全件取得
     */
    public function fetchAllPaymentLinks(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->paymentLinks->all($p),
            $params
        );
    }

    /**
     * チェックアウトセッションを全件取得
     */
    public function fetchAllCheckoutSessions(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->checkout->sessions->all($p),
            $params
        );
    }

    /**
     * 残高取引を全件取得
     */
    public function fetchAllBalanceTransactions(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->balanceTransactions->all($p),
            $params
        );
    }

    /**
     * 振込を全件取得
     */
    public function fetchAllPayouts(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->payouts->all($p),
            $params
        );
    }

    /**
     * イベントを全件取得
     */
    public function fetchAllEvents(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->events->all($p),
            $params
        );
    }

    /**
     * 残高を取得
     */
    public function fetchBalance(): \Stripe\Balance
    {
        return $this->getClient()->balance->retrieve();
    }

    /**
     * 異議申し立てを全件取得
     */
    public function fetchAllDisputes(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->disputes->all($p),
            $params
        );
    }

    /**
     * クレジットノートを全件取得
     */
    public function fetchAllCreditNotes(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->creditNotes->all($p),
            $params
        );
    }

    /**
     * 顧客残高取引を取得（顧客IDが必要）
     */
    public function fetchCustomerBalanceTransactions(string $customerId, array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->customers->allBalanceTransactions($customerId, $p),
            $params
        );
    }

    /**
     * サブスクリプションアイテムを取得（サブスクリプションIDが必要）
     */
    public function fetchSubscriptionItems(string $subscriptionId, array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->subscriptionItems->all(array_merge(['subscription' => $subscriptionId], $p)),
            $params
        );
    }

    // ========================================
    // 拡張Stripeオブジェクト取得メソッド
    // ========================================

    /**
     * クーポンを全件取得
     */
    public function fetchAllCoupons(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->coupons->all($p),
            $params
        );
    }

    /**
     * プロモーションコードを全件取得
     */
    public function fetchAllPromotionCodes(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->promotionCodes->all($p),
            $params
        );
    }

    /**
     * セットアップインテントを全件取得
     */
    public function fetchAllSetupIntents(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->setupIntents->all($p),
            $params
        );
    }

    /**
     * 見積もりを全件取得
     */
    public function fetchAllQuotes(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->quotes->all($p),
            $params
        );
    }

    /**
     * サブスクリプションスケジュールを全件取得
     */
    public function fetchAllSubscriptionSchedules(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->subscriptionSchedules->all($p),
            $params
        );
    }

    /**
     * 税率を全件取得
     */
    public function fetchAllTaxRates(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->taxRates->all($p),
            $params
        );
    }

    /**
     * 税コードを全件取得
     */
    public function fetchAllTaxCodes(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->taxCodes->all($p),
            $params
        );
    }

    /**
     * 送料レートを全件取得
     */
    public function fetchAllShippingRates(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->shippingRates->all($p),
            $params
        );
    }

    /**
     * ファイルを全件取得
     */
    public function fetchAllFiles(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->files->all($p),
            $params
        );
    }

    // ========================================
    // Issuing関連取得メソッド
    // ========================================

    /**
     * Issuingカードホルダーを全件取得
     */
    public function fetchAllIssuingCardholders(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->issuing->cardholders->all($p),
            $params
        );
    }

    /**
     * Issuingカードを全件取得
     */
    public function fetchAllIssuingCards(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->issuing->cards->all($p),
            $params
        );
    }

    /**
     * Issuingオーソリを全件取得
     */
    public function fetchAllIssuingAuthorizations(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->issuing->authorizations->all($p),
            $params
        );
    }

    /**
     * Issuing取引を全件取得
     */
    public function fetchAllIssuingTransactions(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->issuing->transactions->all($p),
            $params
        );
    }

    /**
     * Issuing異議を全件取得
     */
    public function fetchAllIssuingDisputes(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->issuing->disputes->all($p),
            $params
        );
    }

    // ========================================
    // Terminal関連取得メソッド
    // ========================================

    /**
     * Terminalロケーションを全件取得
     */
    public function fetchAllTerminalLocations(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->terminal->locations->all($p),
            $params
        );
    }

    /**
     * Terminalリーダーを全件取得
     */
    public function fetchAllTerminalReaders(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->terminal->readers->all($p),
            $params
        );
    }

    // ========================================
    // Identity関連取得メソッド
    // ========================================

    /**
     * 本人確認セッションを全件取得
     */
    public function fetchAllVerificationSessions(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->identity->verificationSessions->all($p),
            $params
        );
    }

    // ========================================
    // Radar関連取得メソッド
    // ========================================

    /**
     * Radarバリューリストを全件取得
     */
    public function fetchAllRadarValueLists(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->radar->valueLists->all($p),
            $params
        );
    }

    /**
     * Radarバリューリストアイテムを全件取得
     */
    public function fetchAllRadarValueListItems(string $valueListId, array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->radar->valueListItems->all(array_merge(['value_list' => $valueListId], $p)),
            $params
        );
    }

    // ========================================
    // 追加オブジェクト取得メソッド
    // ========================================

    /**
     * 本人確認レポートを全件取得
     */
    public function fetchAllVerificationReports(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->identity->verificationReports->all($p),
            $params
        );
    }

    /**
     * アカウントセッションを取得（リスト取得はサポートされていないため空を返す）
     * Note: Account Sessions are created on-demand and cannot be listed
     */
    public function fetchAllAccountSessions(array $params = []): \Generator
    {
        // Account Sessions cannot be listed via API - they are created on-demand
        return (function () { yield from []; })();
    }

    /**
     * Financial Connectionsアカウントを全件取得
     */
    public function fetchAllFinancialConnectionsAccounts(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->financialConnections->accounts->all($p),
            $params
        );
    }

    /**
     * Financial Connectionsセッションを取得（リスト取得はサポートされていないため空を返す）
     * Note: Financial Connections Sessions are created on-demand and cannot be listed
     */
    public function fetchAllFinancialConnectionsSessions(array $params = []): \Generator
    {
        // Financial Connections Sessions cannot be listed via API - they are created on-demand
        return (function () { yield from []; })();
    }

    /**
     * Treasury Financial Accountを全件取得
     */
    public function fetchAllTreasuryFinancialAccounts(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->treasury->financialAccounts->all($p),
            $params
        );
    }

    /**
     * Treasury Transactionを全件取得
     */
    public function fetchAllTreasuryTransactions(string $financialAccountId, array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->treasury->transactions->all(array_merge(['financial_account' => $financialAccountId], $p)),
            $params
        );
    }

    /**
     * Terminal Connection Tokenを取得（リスト取得はサポートされていないため空を返す）
     * Note: Connection Tokens are created on-demand and cannot be listed
     */
    public function fetchAllTerminalConnectionTokens(array $params = []): \Generator
    {
        // Connection Tokens cannot be listed via API - they are created on-demand
        return (function () { yield from []; })();
    }

    /**
     * Sigma Scheduled Query Runを全件取得
     */
    public function fetchAllSigmaScheduledQueryRuns(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->sigma->scheduledQueryRuns->all($p),
            $params
        );
    }

    /**
     * Reporting Report Runを全件取得
     */
    public function fetchAllReportingReportRuns(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->reporting->reportRuns->all($p),
            $params
        );
    }

    /**
     * Payment Recordを取得（リスト取得はサポートされていないため空を返す）
     * Note: Payment Records API may have limited availability
     */
    public function fetchAllPaymentRecords(array $params = []): \Generator
    {
        // Payment Records may not support list operation in all accounts
        return (function () { yield from []; })();
    }

    // ========================================
    // 追加取得メソッド（残りのStripeオブジェクト）
    // ========================================

    /**
     * Country Specを全件取得
     */
    public function fetchAllCountrySpecs(array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->countrySpecs->all($p),
            $params
        );
    }

    /**
     * Mandateを取得（IDで個別取得のみ）
     */
    public function fetchMandate(string $mandateId): ?\Stripe\Mandate
    {
        try {
            return $this->getClient()->mandates->retrieve($mandateId);
        } catch (\Exception $e) {
            Log::warning('Failed to fetch mandate: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * PaymentIntentに関連するMandateを取得
     */
    public function fetchMandatesFromPaymentIntents(array $params = []): \Generator
    {
        $seenMandates = [];
        foreach ($this->fetchAllPaymentIntents($params) as $pi) {
            if (!empty($pi->mandate) && !isset($seenMandates[$pi->mandate])) {
                $mandate = $this->fetchMandate($pi->mandate);
                if ($mandate) {
                    $seenMandates[$pi->mandate] = true;
                    yield $mandate;
                }
            }
        }
    }

    /**
     * SetupIntentに関連するMandateを取得
     */
    public function fetchMandatesFromSetupIntents(array $params = []): \Generator
    {
        $seenMandates = [];
        foreach ($this->fetchAllSetupIntents($params) as $si) {
            if (!empty($si->mandate) && !isset($seenMandates[$si->mandate])) {
                $mandate = $this->fetchMandate($si->mandate);
                if ($mandate) {
                    $seenMandates[$si->mandate] = true;
                    yield $mandate;
                }
            }
        }
    }

    /**
     * 全てのMandateを取得（PaymentIntentとSetupIntentから）
     */
    public function fetchAllMandates(array $params = []): \Generator
    {
        $seenMandates = [];

        // PaymentIntentsから
        foreach ($this->fetchAllPaymentIntents($params) as $pi) {
            if (!empty($pi->mandate) && !isset($seenMandates[$pi->mandate])) {
                $mandate = $this->fetchMandate($pi->mandate);
                if ($mandate) {
                    $seenMandates[$pi->mandate] = true;
                    yield $mandate;
                }
            }
        }

        // SetupIntentsから
        foreach ($this->fetchAllSetupIntents($params) as $si) {
            if (!empty($si->mandate) && !isset($seenMandates[$si->mandate])) {
                $mandate = $this->fetchMandate($si->mandate);
                if ($mandate) {
                    $seenMandates[$si->mandate] = true;
                    yield $mandate;
                }
            }
        }
    }

    /**
     * Sourceを取得（顧客ごと）
     */
    public function fetchSourcesForCustomer(string $customerId, array $params = []): \Generator
    {
        return $this->fetchAllWithPagination(
            fn($p) => $this->getClient()->customers->allSources($customerId, array_merge(['object' => 'source'], $p)),
            $params
        );
    }

    /**
     * 全顧客のSourceを取得
     */
    public function fetchAllSources(array $params = []): \Generator
    {
        foreach ($this->fetchAllCustomers($params) as $customer) {
            foreach ($this->fetchSourcesForCustomer($customer->id) as $source) {
                yield $source;
            }
        }
    }

    /**
     * Radar Value List Itemを全件取得
     */
    public function fetchAllRadarValueListItemsAll(array $params = []): \Generator
    {
        // 全てのValue Listを取得してそれぞれのアイテムを取得
        foreach ($this->fetchAllRadarValueLists($params) as $valueList) {
            foreach ($this->fetchAllRadarValueListItems($valueList->id) as $item) {
                yield $item;
            }
        }
    }
}
