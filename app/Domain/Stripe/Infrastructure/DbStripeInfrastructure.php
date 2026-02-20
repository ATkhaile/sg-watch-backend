<?php

namespace App\Domain\Stripe\Infrastructure;

use App\Domain\Stripe\Repository\StripeRepository;
use App\Domain\Stripe\Entity\CreateCustomerRequestEntity;
use App\Domain\Stripe\Entity\GetPortalLinkRequestEntity;
use App\Domain\Stripe\Entity\RequestCancelRequestEntity;
use App\Domain\Stripe\Entity\SubmitCancelRequestEntity;
use App\Domain\Stripe\Entity\CheckCancelCodeRequestEntity;
use App\Domain\Stripe\Entity\StatusEntity;
use App\Domain\Stripe\Entity\PortalLinkResponseEntity;
use App\Domain\Stripe\Entity\CancelResponseEntity;
use App\Enums\StatusCode;
use App\Mail\CancelRequest;
use App\Mail\RegisterUser;
use App\Models\Plan;
use App\Models\Prefecture;
use App\Models\Stripe\StripeAccount;
use App\Models\Stripe\StripeDashboardStats;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Stripe\Webhook;
use Stripe\StripeClient;

class DbStripeInfrastructure implements StripeRepository
{
    private $prefix_cancel_key = 'cancel_';

    public function handleWebhook(CreateCustomerRequestEntity $requestEntity): StatusEntity
    {
        $filePath = 'webhook-log.txt';

        try {
            [$event, $successSecret] = $this->constructEventWithValidSecret(
                $requestEntity->payload,
                $requestEntity->signature
            );
        } catch (\Exception $e) {
            Storage::append($filePath, '------------------------------------');
            Storage::append($filePath, Carbon::now()->format('Y-m-d H-i'));
            Storage::append($filePath, 'Payload');
            Storage::append($filePath, json_encode($requestEntity->payload));
            Storage::append($filePath, 'Stripe-Signature in Header');
            Storage::append($filePath, $requestEntity->signature);
            Storage::append($filePath, 'STRIPE_WEBHOOK_SECRET');
            Storage::append($filePath, $requestEntity->endpointSecret);
            Storage::append($filePath, 'Message');
            Storage::append($filePath, $e->getMessage());
            Storage::append($filePath, '------------------------------------');

            return new StatusEntity(
                statusCode: new StatusCode(StatusCode::UNPROCESSABLE_ENTITY),
                message: 'Error processing webhook'
            );
        }

        try {
            DB::beginTransaction();
            $customer = null;
            $product_stripe_id = null;
            $trial_end = null;

            switch ($event->type) {
                case 'checkout.session.completed':
                    $subscriptionId = $event->data->object->subscription;
                    $stripe = $this->getStripeClient($successSecret);
                    $subscription = $stripe->subscriptions->retrieve($subscriptionId, []);
                    $trial_end = $subscription->trial_end;
                    $session_stripe_id = $event->data->object->id ?? '';
                    $customer = $event->data->object->customer_details;
                    $product = $this->getItems($session_stripe_id, $successSecret);
                    $product_stripe_id = $product[0]->price->product;
                    break;
                case 'customer.subscription.updated':
                    $stripe = $this->getStripeClient($successSecret);
                    $product = $event->data->object->items->data;
                    $product_stripe_id = $product[0]->price->product;
                    $customer = $stripe->customers->retrieve($event->data->object->customer, []);
                    $trial_end = $event->data->object->trial_end;
                    break;
                case 'charge.failed':
                    $email = $event->data->object->billing_details->email;
                    $checkUser = User::where('email', $email)->where('is_system_admin', false)->first();

                    Storage::append($filePath, Carbon::now()->format('Y-m-d H-i'));
                    Storage::append($filePath, 'email');
                    Storage::append($filePath, json_encode($event->data->object));
                    Storage::append($filePath, '------------------------------------');

                    if ($checkUser) {
                        $checkUser->update([
                            'active' => false
                        ]);
                    }
                    break;
                default:
                    $customer = null;
                    $product_stripe_id = null;
            }

            if ($customer && $product_stripe_id) {
                $this->handleUser($customer, $product_stripe_id, $trial_end);
            }

            DB::commit();
            return new StatusEntity(
                statusCode: new StatusCode(StatusCode::OK),
                message: 'Webhook processed successfully'
            );
        } catch (\Exception $e) {
            Storage::append($filePath, Carbon::now()->format('Y-m-d H-i'));
            Storage::append($filePath, 'Error');
            Storage::append($filePath, $e->getMessage());
            Storage::append($filePath, '------------------------------------');
            DB::rollBack();

            return new StatusEntity(
                statusCode: new StatusCode(StatusCode::UNPROCESSABLE_ENTITY),
                message: $e->getMessage()
            );
        }
    }
    public function createPortalLink(GetPortalLinkRequestEntity $requestEntity): PortalLinkResponseEntity
    {
        $user = auth()->user() ?? null;
        $plan = Plan::where('plan_id', $user->plan_id)->first();
        $stripe_webhook_secret = $plan->stripe_webhook_secret ?? null;
        $portal_link = $this->createStripePortalLink($stripe_webhook_secret, $requestEntity->userEmail);

        return new PortalLinkResponseEntity(
            portalLink: $portal_link,
            statusCode: new StatusCode(StatusCode::OK),
            message: 'Portal link created successfully'
        );
    }

    public function requestCancel(RequestCancelRequestEntity $requestEntity): StatusEntity
    {
        if (!$requestEntity->email || $requestEntity->email != auth()->user()->email) {
            return new StatusEntity(
                statusCode: new StatusCode(StatusCode::UNPROCESSABLE_ENTITY),
                message: 'メールアドレスが無効です'
            );
        }

        $request_code = uniqid();
        Cache::put($this->prefix_cancel_key . $request_code, $requestEntity->userId, $seconds = 30 * 60);

        Mail::to($requestEntity->email)->send(new CancelRequest([
            'name' => $requestEntity->userName,
            'link' => env('BASE_FRONTEND_URL') . 'unsubscribe?request_code=' . $request_code,
        ]));

        return new StatusEntity(
            statusCode: new StatusCode(StatusCode::OK),
            message: 'メールが正常に送信されました!',
            data: ['redirect' => 'cancel.application']
        );
    }

    public function submitCancel(SubmitCancelRequestEntity $requestEntity): CancelResponseEntity
    {
        $user_id = Cache::get($this->prefix_cancel_key . $requestEntity->requestCode);

        if (!$user_id) {
            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::UNPROCESSABLE_ENTITY),
                message: 'リンクの有効期限が切れました。!',
                redirect: 'cancel'
            );
        }

        $user = User::find($user_id);
        if (!$user) {
            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::UNPROCESSABLE_ENTITY),
                message: 'ユーザーが存在しません!',
                redirect: 'cancel'
            );
        }

        if (!$requestEntity->reason) {
            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::UNPROCESSABLE_ENTITY),
                message: 'キャンセルの理由を入力してください'
            );
        }

        if (!$requestEntity->password) {
            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::UNPROCESSABLE_ENTITY),
                message: 'パスワードを入力してください'
            );
        }

        if (!Hash::check($requestEntity->password, $user->password)) {
            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::UNPROCESSABLE_ENTITY),
                message: 'あなたのパスワードは正しくありません'
            );
        }

        $plan = Plan::where('plan_id', $user->plan_id)->first();
        $cancel_hours = $plan->cancel_hours ?? null;

        if ($cancel_hours == null) {
            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::INTERNAL_ERR),
                message: 'このプランの登録をキャンセルすることはできません。'
            );
        }

        $stripe_webhook_secret = $plan->stripe_webhook_secret ?? null;
        $stripe = $this->getStripeClient($stripe_webhook_secret);
        $customer = $stripe->customers->search([
            'query' => "email:'{$user->email}'",
            'limit' => 1
        ]);

        $customerId = $customer->data[0]->id ?? null;
        if (!$customerId) {
            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::INTERNAL_ERR),
                message: '顧客が存在しません。',
                redirect: 'cancel.error.payment'
            );
        }

        $subscriptions = $stripe->subscriptions->all([
            'limit' => 1,
            'customer' => $customerId,
        ]);

        $subscription = ($subscriptions->data)[0] ?? null;
        if (!$subscription) {
            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::INTERNAL_ERR),
                message: 'あなたはまだ登録されているプランがありません',
                redirect: 'cancel.error.payment'
            );
        }

        if (Carbon::now()->subHours((float)$cancel_hours)->timestamp > $subscription->start_date) {
            try {
                $stripe->subscriptions->cancel($subscription->id, []);
            } catch (\Exception $e) {
                return new CancelResponseEntity(
                    statusCode: new StatusCode(StatusCode::INTERNAL_ERR),
                    message: $e->getMessage(),
                    redirect: 'cancel.error.payment'
                );
            }

            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::OK),
                message: 'Success!',
                data: [
                    'plan' => $plan,
                    'cancel_time' => Carbon::now()->format('Y/m/d H:i')
                ],
                redirect: 'cancel.success'
            );
        } else {
            return new CancelResponseEntity(
                statusCode: new StatusCode(StatusCode::INTERNAL_ERR),
                message: "登録から $cancel_hours 時間経過後にプランをキャンセルしてください。",
                data: [
                    'plan' => $plan,
                    'cancel_hours' => $cancel_hours
                ],
                redirect: 'cancel.error'
            );
        }
    }

    public function checkCancelCode(CheckCancelCodeRequestEntity $requestEntity): StatusEntity
    {
        $user_id = Cache::get($this->prefix_cancel_key . $requestEntity->requestCode);

        if (!$user_id) {
            return new StatusEntity(
                statusCode: new StatusCode(StatusCode::BAD_REQUEST),
                message: 'リンクの有効期限が切れました。!',
                data: ['redirect' => 'cancel']
            );
        }

        return new StatusEntity(
            statusCode: new StatusCode(StatusCode::OK),
            message: 'Success'
        );
    }
    private function getItems(string $session_stripe_id, $successSecret)
    {
        $stripe = $this->getStripeClient($successSecret);
        $data = $stripe->checkout->sessions->allLineItems(
            $session_stripe_id,
            []
        );
        return $data->data;
    }

    private function handleUser($customer, $product_stripe_id, $trial_end = null)
    {
        $plan = Plan::where('stripe_plan_id', $product_stripe_id)->first();
        $prefecture = Prefecture::first();
        $email = $customer->email;
        $name = $customer->name;
        $phone = $customer->phone;

        $snsLimits = $plan->sns_limits ?? [];
        $snsDeveloper = $plan->sns_developer ?? [];
        $currentTime = time();
        $is_trial = $trial_end && $trial_end > $currentTime ? 1 : 0;

        $checkUser = User::where('email', $email)->where('is_system_admin', false)->first();
        if ($checkUser && $plan) {
            $checkUser->update([
                'plan_id' => $plan->plan_id,
                'active' => true,
                'sns_limits' => $snsLimits,
                'sns_developer' => $snsDeveloper,
                'trial' => $is_trial
            ]);
        } elseif ($plan) {
            $password = 'Game' . '-' . substr(str_shuffle(uniqid()), 0, 3) . '@' . rand(1000, 9999);

            $user_id = $this->generateUniqueUserId($email);

            sleep(30);
            $portal_link = $this->createStripePortalLink($plan->stripe_webhook_secret, $email, $name);
            Mail::to($email)->send(new RegisterUser([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'plan_name' => $plan->name ?? '',
                'url' => env('BASE_FRONTEND_URL') . 'login',
                'portal_link' => $portal_link ?? null
            ]));
            User::create([
                'user_id' => $user_id,
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone,
                'password' => Hash::make($password),
                'plan_id' => $plan->plan_id,
                'prefecture_id' => $prefecture->prefecture_id,
                'is_system_admin' => false,
                'active' => true,
                'sns_limits' => $snsLimits,
                'sns_developer' => $snsDeveloper,
                'trial' => $is_trial
            ]);
        }
    }

    /**
     * Get the Stripe client instance
     */
    private function getStripeClient($stripe_webhook_secret = null): StripeClient
    {
        $plan = Plan::where('stripe_webhook_secret', $stripe_webhook_secret)->first();
        return new StripeClient($plan->stripe_secret);
    }

    /**
     * Create a Stripe portal link for customer management
     */
    private function createStripePortalLink(string $stripe_webhook_secret, string $email, string $name = null): ?string
    {
        try {
            $stripe = $this->getStripeClient($stripe_webhook_secret);
            $customer = null;

            if ($name) {
                $customer = $stripe->customers->search([
                    'query' => "name:'{$name}' AND email:'{$email}'",
                ]);
            } else {
                $customer = $stripe->customers->search([
                    'query' => "email:'{$email}'",
                ]);
            }

            $customer_id = ($customer?->data)[0]?->id;
            $portal = $stripe->billingPortal->sessions->create([
                'customer' => $customer_id,
                'return_url' => env('BASE_FRONTEND_URL') . 'login',
            ]);

            return $portal->url;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function generateUniqueUserId(string $email): string
    {
        do {
            $emailPrefix = substr(str_replace('@', '', explode('@', $email)[0]), 0, 8);
            $timestamp = substr(time(), -6);
            $random = substr(str_shuffle('0123456789ABCDEF'), 0, 4);
            $user_id = strtoupper($emailPrefix . $timestamp . $random);

            $exists = User::where('user_id', $user_id)->exists();
        } while ($exists);

        return $user_id;
    }

    private function constructEventWithValidSecret(string $payload, string $signature): array
    {
        $allSecrets = Plan::whereNotNull('stripe_webhook_secret')
            ->pluck('stripe_webhook_secret')
            ->toArray();
        $lastException = null;

        foreach ($allSecrets as $webhookSecret) {
            try {
                $event = Webhook::constructEvent(
                    $payload,
                    $signature,
                    $webhookSecret
                );
                return [$event, $webhookSecret];
            } catch (\Exception $e) {
                $lastException = $e;
            }
        }

        throw $lastException;
    }

    public function getDashboardStats(int $id): ?array
    {
        $account = StripeAccount::find($id);
        if (!$account) {
            return null;
        }

        $stats = StripeDashboardStats::where('stripe_account_id', $account->uuid)->first();
        $dbCounts = $this->getLocalDbCounts($account->id);

        return [
            'id' => $account->id,
            'stripe_account_id' => $account->uuid,
            'stripe_account_name' => $account->display_name,
            'stripe' => [
                // コアデータ
                'customers_count' => $stats->customers_count ?? 0,
                'products_count' => $stats->products_count ?? 0,
                'prices_count' => $stats->prices_count ?? 0,
                'subscriptions_count' => $stats->subscriptions_count ?? 0,
                'subscription_items_count' => $stats->subscription_items_count ?? 0,
                'invoices_count' => $stats->invoices_count ?? 0,
                'invoice_items_count' => $stats->invoice_items_count ?? 0,
                'charges_count' => $stats->charges_count ?? 0,
                'payment_intents_count' => $stats->payment_intents_count ?? 0,
                'refunds_count' => $stats->refunds_count ?? 0,
                'payment_methods_count' => $stats->payment_methods_count ?? 0,
                'payment_links_count' => $stats->payment_links_count ?? 0,
                'checkout_sessions_count' => $stats->checkout_sessions_count ?? 0,
                // 財務データ
                'disputes_count' => $stats->disputes_count ?? 0,
                'credit_notes_count' => $stats->credit_notes_count ?? 0,
                'events_count' => $stats->events_count ?? 0,
                'balance_transactions_count' => $stats->balance_transactions_count ?? 0,
                'payouts_count' => $stats->payouts_count ?? 0,
                // 拡張データ
                'coupons_count' => $stats->coupons_count ?? 0,
                'promotion_codes_count' => $stats->promotion_codes_count ?? 0,
                'setup_intents_count' => $stats->setup_intents_count ?? 0,
                'quotes_count' => $stats->quotes_count ?? 0,
                'subscription_schedules_count' => $stats->subscription_schedules_count ?? 0,
                'tax_rates_count' => $stats->tax_rates_count ?? 0,
                'shipping_rates_count' => $stats->shipping_rates_count ?? 0,
                'files_count' => $stats->files_count ?? 0,
                'country_specs_count' => $stats->country_specs_count ?? 0,
                // Issuing
                'issuing_cardholders_count' => $stats->issuing_cardholders_count ?? 0,
                'issuing_cards_count' => $stats->issuing_cards_count ?? 0,
                'issuing_authorizations_count' => $stats->issuing_authorizations_count ?? 0,
                'issuing_transactions_count' => $stats->issuing_transactions_count ?? 0,
                'issuing_disputes_count' => $stats->issuing_disputes_count ?? 0,
                // Terminal・Identity・Radar
                'terminal_locations_count' => $stats->terminal_locations_count ?? 0,
                'terminal_readers_count' => $stats->terminal_readers_count ?? 0,
                'verification_sessions_count' => $stats->verification_sessions_count ?? 0,
                'verification_reports_count' => $stats->verification_reports_count ?? 0,
                'radar_value_lists_count' => $stats->radar_value_lists_count ?? 0,
                // Financial Connections・Treasury
                'financial_connections_accounts_count' => $stats->financial_connections_accounts_count ?? 0,
                'treasury_financial_accounts_count' => $stats->treasury_financial_accounts_count ?? 0,
                // Sigma・Reporting
                'sigma_scheduled_query_runs_count' => $stats->sigma_scheduled_query_runs_count ?? 0,
                'reporting_report_runs_count' => $stats->reporting_report_runs_count ?? 0,
                'last_synced_at' => $stats->last_synced_at ?? null,
            ],
            'local_db' => $dbCounts,
        ];
    }

    /**
     * ローカルDBに同期されたStripeデータの件数を取得
     */
    private function getLocalDbCounts(int $accountId): array
    {
        return [
            // コアデータ
            'customers_count' => \App\Models\Stripe\StripeCustomer::where('stripe_account_id', $accountId)->count(),
            'products_count' => \App\Models\Stripe\StripeProduct::where('stripe_account_id', $accountId)->count(),
            'prices_count' => \App\Models\Stripe\StripePrice::where('stripe_account_id', $accountId)->count(),
            'payment_intents_count' => \App\Models\Stripe\StripePaymentIntent::where('stripe_account_id', $accountId)->count(),
            'charges_count' => \App\Models\Stripe\StripeCharge::where('stripe_account_id', $accountId)->count(),
            'subscriptions_count' => \App\Models\Stripe\StripeSubscription::where('stripe_account_id', $accountId)->count(),
            'subscription_items_count' => \App\Models\Stripe\StripeSubscriptionItem::where('stripe_account_id', $accountId)->count(),
            'invoices_count' => \App\Models\Stripe\StripeInvoice::where('stripe_account_id', $accountId)->count(),
            'invoice_items_count' => \App\Models\Stripe\StripeInvoiceItem::where('stripe_account_id', $accountId)->count(),
            'refunds_count' => \App\Models\Stripe\StripeRefund::where('stripe_account_id', $accountId)->count(),
            'payment_methods_count' => \App\Models\Stripe\StripePaymentMethod::where('stripe_account_id', $accountId)->count(),
            'payment_links_count' => \App\Models\Stripe\StripePaymentLink::where('stripe_account_id', $accountId)->count(),
            'checkout_sessions_count' => \App\Models\Stripe\StripeCheckoutSession::where('stripe_account_id', $accountId)->count(),
            // 財務データ
            'balances_count' => \App\Models\Stripe\StripeBalance::where('stripe_account_id', $accountId)->count(),
            'balance_transactions_count' => \App\Models\Stripe\StripeBalanceTransaction::where('stripe_account_id', $accountId)->count(),
            'payouts_count' => \App\Models\Stripe\StripePayout::where('stripe_account_id', $accountId)->count(),
            'disputes_count' => \App\Models\Stripe\StripeDispute::where('stripe_account_id', $accountId)->count(),
            'credit_notes_count' => \App\Models\Stripe\StripeCreditNote::where('stripe_account_id', $accountId)->count(),
            'customer_balance_transactions_count' => \App\Models\Stripe\StripeCustomerBalanceTransaction::where('stripe_account_id', $accountId)->count(),
            // イベント
            'events_count' => \App\Models\Stripe\StripeEvent::where('stripe_account_id', $accountId)->count(),
            // 同期管理
            'webhook_events_count' => \App\Models\Stripe\StripeWebhookEvent::where('stripe_account_id', $accountId)->count(),
            'sync_jobs_count' => \App\Models\Stripe\StripeSyncJob::where('stripe_account_id', $accountId)->count(),
            'sync_errors_count' => \App\Models\Stripe\StripeSyncError::where('stripe_account_id', $accountId)->count(),
            // 拡張データ - クーポン・プロモーション
            'coupons_count' => \App\Models\Stripe\StripeCoupon::where('stripe_account_id', $accountId)->count(),
            'promotion_codes_count' => \App\Models\Stripe\StripePromotionCode::where('stripe_account_id', $accountId)->count(),
            // 拡張データ - Setup Intent・Quote・Subscription Schedule
            'setup_intents_count' => \App\Models\Stripe\StripeSetupIntent::where('stripe_account_id', $accountId)->count(),
            'quotes_count' => \App\Models\Stripe\StripeQuote::where('stripe_account_id', $accountId)->count(),
            'subscription_schedules_count' => \App\Models\Stripe\StripeSubscriptionSchedule::where('stripe_account_id', $accountId)->count(),
            // 拡張データ - Tax・Shipping
            'tax_rates_count' => \App\Models\Stripe\StripeTaxRate::where('stripe_account_id', $accountId)->count(),
            'tax_codes_count' => \App\Models\Stripe\StripeTaxCode::where('stripe_account_id', $accountId)->count(),
            'shipping_rates_count' => \App\Models\Stripe\StripeShippingRate::where('stripe_account_id', $accountId)->count(),
            // 拡張データ - Files
            'files_count' => \App\Models\Stripe\StripeFile::where('stripe_account_id', $accountId)->count(),
            // Issuing データ
            'issuing_cardholders_count' => \App\Models\Stripe\StripeIssuingCardholder::where('stripe_account_id', $accountId)->count(),
            'issuing_cards_count' => \App\Models\Stripe\StripeIssuingCard::where('stripe_account_id', $accountId)->count(),
            'issuing_authorizations_count' => \App\Models\Stripe\StripeIssuingAuthorization::where('stripe_account_id', $accountId)->count(),
            'issuing_transactions_count' => \App\Models\Stripe\StripeIssuingTransaction::where('stripe_account_id', $accountId)->count(),
            'issuing_disputes_count' => \App\Models\Stripe\StripeIssuingDispute::where('stripe_account_id', $accountId)->count(),
            // Terminal データ
            'terminal_locations_count' => \App\Models\Stripe\StripeTerminalLocation::where('stripe_account_id', $accountId)->count(),
            'terminal_readers_count' => \App\Models\Stripe\StripeTerminalReader::where('stripe_account_id', $accountId)->count(),
            // Identity・Radar データ
            'verification_sessions_count' => \App\Models\Stripe\StripeVerificationSession::where('stripe_account_id', $accountId)->count(),
            'verification_reports_count' => \App\Models\Stripe\StripeVerificationReport::where('stripe_account_id', $accountId)->count(),
            'radar_value_lists_count' => \App\Models\Stripe\StripeRadarValueList::where('stripe_account_id', $accountId)->count(),
            'radar_value_list_items_count' => \App\Models\Stripe\StripeRadarValueListItem::where('stripe_account_id', $accountId)->count(),
            // 追加オブジェクト
            'account_sessions_count' => \App\Models\Stripe\StripeAccountSession::where('stripe_account_id', $accountId)->count(),
            'financial_connections_accounts_count' => \App\Models\Stripe\StripeFinancialConnectionsAccount::where('stripe_account_id', $accountId)->count(),
            'financial_connections_sessions_count' => \App\Models\Stripe\StripeFinancialConnectionsSession::where('stripe_account_id', $accountId)->count(),
            'treasury_financial_accounts_count' => \App\Models\Stripe\StripeTreasuryFinancialAccount::where('stripe_account_id', $accountId)->count(),
            'treasury_transactions_count' => \App\Models\Stripe\StripeTreasuryTransaction::where('stripe_account_id', $accountId)->count(),
            'terminal_connection_tokens_count' => \App\Models\Stripe\StripeTerminalConnectionToken::where('stripe_account_id', $accountId)->count(),
            'sigma_scheduled_query_runs_count' => \App\Models\Stripe\StripeSigmaScheduledQueryRun::where('stripe_account_id', $accountId)->count(),
            'reporting_report_runs_count' => \App\Models\Stripe\StripeReportingReportRun::where('stripe_account_id', $accountId)->count(),
            'payment_records_count' => \App\Models\Stripe\StripePaymentRecord::where('stripe_account_id', $accountId)->count(),
            // 追加オブジェクト（残り）
            'country_specs_count' => \App\Models\Stripe\StripeCountrySpec::where('stripe_account_id', $accountId)->count(),
            'mandates_count' => \App\Models\Stripe\StripeMandate::where('stripe_account_id', $accountId)->count(),
            'sources_count' => \App\Models\Stripe\StripeSource::where('stripe_account_id', $accountId)->count(),
        ];
    }

    public function getAllDashboardStats(): array
    {
        $accounts = StripeAccount::all();

        return $accounts->map(function ($account) {
            $stats = StripeDashboardStats::where('stripe_account_id', $account->uuid)->first();
            $dbCounts = $this->getLocalDbCounts($account->id);

            return [
                'id' => $account->id,
                'stripe_account_id' => $account->uuid,
                'stripe_account_name' => $account->display_name ?? '',
                'stripe' => [
                    // コアデータ
                    'customers_count' => $stats->customers_count ?? 0,
                    'products_count' => $stats->products_count ?? 0,
                    'prices_count' => $stats->prices_count ?? 0,
                    'subscriptions_count' => $stats->subscriptions_count ?? 0,
                    'subscription_items_count' => $stats->subscription_items_count ?? 0,
                    'invoices_count' => $stats->invoices_count ?? 0,
                    'invoice_items_count' => $stats->invoice_items_count ?? 0,
                    'charges_count' => $stats->charges_count ?? 0,
                    'payment_intents_count' => $stats->payment_intents_count ?? 0,
                    'refunds_count' => $stats->refunds_count ?? 0,
                    'payment_methods_count' => $stats->payment_methods_count ?? 0,
                    'payment_links_count' => $stats->payment_links_count ?? 0,
                    'checkout_sessions_count' => $stats->checkout_sessions_count ?? 0,
                    // 財務データ
                    'disputes_count' => $stats->disputes_count ?? 0,
                    'credit_notes_count' => $stats->credit_notes_count ?? 0,
                    'events_count' => $stats->events_count ?? 0,
                    'balance_transactions_count' => $stats->balance_transactions_count ?? 0,
                    'payouts_count' => $stats->payouts_count ?? 0,
                    // 拡張データ
                    'coupons_count' => $stats->coupons_count ?? 0,
                    'promotion_codes_count' => $stats->promotion_codes_count ?? 0,
                    'setup_intents_count' => $stats->setup_intents_count ?? 0,
                    'quotes_count' => $stats->quotes_count ?? 0,
                    'subscription_schedules_count' => $stats->subscription_schedules_count ?? 0,
                    'tax_rates_count' => $stats->tax_rates_count ?? 0,
                    'shipping_rates_count' => $stats->shipping_rates_count ?? 0,
                    'files_count' => $stats->files_count ?? 0,
                    'country_specs_count' => $stats->country_specs_count ?? 0,
                    // Issuing
                    'issuing_cardholders_count' => $stats->issuing_cardholders_count ?? 0,
                    'issuing_cards_count' => $stats->issuing_cards_count ?? 0,
                    'issuing_authorizations_count' => $stats->issuing_authorizations_count ?? 0,
                    'issuing_transactions_count' => $stats->issuing_transactions_count ?? 0,
                    'issuing_disputes_count' => $stats->issuing_disputes_count ?? 0,
                    // Terminal・Identity・Radar
                    'terminal_locations_count' => $stats->terminal_locations_count ?? 0,
                    'terminal_readers_count' => $stats->terminal_readers_count ?? 0,
                    'verification_sessions_count' => $stats->verification_sessions_count ?? 0,
                    'verification_reports_count' => $stats->verification_reports_count ?? 0,
                    'radar_value_lists_count' => $stats->radar_value_lists_count ?? 0,
                    // Financial Connections・Treasury
                    'financial_connections_accounts_count' => $stats->financial_connections_accounts_count ?? 0,
                    'treasury_financial_accounts_count' => $stats->treasury_financial_accounts_count ?? 0,
                    // Sigma・Reporting
                    'sigma_scheduled_query_runs_count' => $stats->sigma_scheduled_query_runs_count ?? 0,
                    'reporting_report_runs_count' => $stats->reporting_report_runs_count ?? 0,
                    'last_synced_at' => $stats->last_synced_at ?? null,
                ],
                'local_db' => $dbCounts,
            ];
        })->toArray();
    }

    public function refreshDashboardStats(int $id): array
    {
        // Stripe API呼び出しが多いため、タイムアウトを延長
        set_time_limit(300);

        $account = StripeAccount::find($id);

        if (!$account) {
            throw new \Exception(__('stripe_account.dashboard_stats.errors.account_not_found'));
        }

        if (!$account->secret_key) {
            throw new \Exception(__('stripe_account.dashboard_stats.errors.api_key_not_configured'));
        }

        $stripe = new StripeClient($account->secret_key);

        // Helper function to count all items with pagination
        $countAll = function($listMethod) use ($stripe) {
            $count = 0;
            $hasMore = true;
            $startingAfter = null;

            while ($hasMore) {
                try {
                    $options = ['limit' => 100];
                    if ($startingAfter) {
                        $options['starting_after'] = $startingAfter;
                    }

                    $response = $listMethod($options);
                    $count += count($response->data);
                    $hasMore = $response->has_more ?? false;

                    if ($hasMore && !empty($response->data)) {
                        $startingAfter = end($response->data)->id;
                    } else {
                        $hasMore = false;
                    }
                } catch (\Exception $e) {
                    $hasMore = false;
                }
            }

            return $count;
        };

        // Count subscription items by iterating through subscriptions
        $subscriptionItemsCount = 0;
        try {
            $subscriptions = $stripe->subscriptions->all(['limit' => 100]);
            foreach ($subscriptions->data as $subscription) {
                $subscriptionItemsCount += count($subscription->items->data);
            }
            // Handle pagination for subscriptions too
            while ($subscriptions->has_more ?? false) {
                $lastId = end($subscriptions->data)->id;
                $subscriptions = $stripe->subscriptions->all(['limit' => 100, 'starting_after' => $lastId]);
                foreach ($subscriptions->data as $subscription) {
                    $subscriptionItemsCount += count($subscription->items->data);
                }
            }
        } catch (\Exception $e) {
            // If error, keep count as 0
        }

        // Count payment link line items by iterating through payment links
        $paymentLinkLineItemsCount = 0;
        try {
            $paymentLinks = $stripe->paymentLinks->all(['limit' => 100]);
            foreach ($paymentLinks->data as $paymentLink) {
                $paymentLinkLineItemsCount += count($paymentLink->line_items ?? []);
            }
            // Handle pagination for payment links too
            while ($paymentLinks->has_more ?? false) {
                $lastId = end($paymentLinks->data)->id;
                $paymentLinks = $stripe->paymentLinks->all(['limit' => 100, 'starting_after' => $lastId]);
                foreach ($paymentLinks->data as $paymentLink) {
                    $paymentLinkLineItemsCount += count($paymentLink->line_items ?? []);
                }
            }
        } catch (\Exception $e) {
            // If error, keep count as 0
        }

        // Count payment methods by iterating through customers
        $paymentMethodsCount = 0;
        try {
            $customers = $stripe->customers->all(['limit' => 100]);
            foreach ($customers->data as $customer) {
                try {
                    $paymentMethods = $stripe->paymentMethods->all(['customer' => $customer->id, 'limit' => 100]);
                    $paymentMethodsCount += count($paymentMethods->data);
                    while ($paymentMethods->has_more ?? false) {
                        $lastPmId = end($paymentMethods->data)->id;
                        $paymentMethods = $stripe->paymentMethods->all(['customer' => $customer->id, 'limit' => 100, 'starting_after' => $lastPmId]);
                        $paymentMethodsCount += count($paymentMethods->data);
                    }
                } catch (\Exception $e) {
                    // Skip this customer if error
                }
            }
            // Handle pagination for customers too
            while ($customers->has_more ?? false) {
                $lastId = end($customers->data)->id;
                $customers = $stripe->customers->all(['limit' => 100, 'starting_after' => $lastId]);
                foreach ($customers->data as $customer) {
                    try {
                        $paymentMethods = $stripe->paymentMethods->all(['customer' => $customer->id, 'limit' => 100]);
                        $paymentMethodsCount += count($paymentMethods->data);
                        while ($paymentMethods->has_more ?? false) {
                            $lastPmId = end($paymentMethods->data)->id;
                            $paymentMethods = $stripe->paymentMethods->all(['customer' => $customer->id, 'limit' => 100, 'starting_after' => $lastPmId]);
                            $paymentMethodsCount += count($paymentMethods->data);
                        }
                    } catch (\Exception $e) {
                        // Skip this customer if error
                    }
                }
            }
        } catch (\Exception $e) {
            // If error, keep count as 0
        }

        // Fetch all counts from Stripe API with proper filters
        $statsData = [
            'id' => $account->id,
            'stripe_account_id' => $account->uuid,
            'stripe_account_name' => $account->display_name ?? '',
            // コアデータ
            'payment_links_count' => $countAll(fn($opts) => $stripe->paymentLinks->all($opts)),
            'prices_count' => $countAll(fn($opts) => $stripe->prices->all($opts)),
            'products_count' => $countAll(fn($opts) => $stripe->products->all($opts)),
            'customers_count' => $countAll(fn($opts) => $stripe->customers->all($opts)),
            'subscriptions_count' => $countAll(fn($opts) => $stripe->subscriptions->all(array_merge($opts, ['status' => 'all']))),
            'invoices_count' => $countAll(fn($opts) => $stripe->invoices->all($opts)),
            'charges_count' => $countAll(fn($opts) => $stripe->charges->all($opts)),
            'payment_intents_count' => $countAll(fn($opts) => $stripe->paymentIntents->all($opts)),
            'refunds_count' => $countAll(fn($opts) => $stripe->refunds->all($opts)),
            'events_count' => $countAll(fn($opts) => $stripe->events->all($opts)),
            'balance_transactions_count' => $countAll(fn($opts) => $stripe->balanceTransactions->all($opts)),
            'checkout_sessions_count' => $countAll(fn($opts) => $stripe->checkout->sessions->all($opts)),
            'invoice_items_count' => $countAll(fn($opts) => $stripe->invoiceItems->all($opts)),
            'payouts_count' => $countAll(fn($opts) => $stripe->payouts->all($opts)),
            'disputes_count' => $countAll(fn($opts) => $stripe->disputes->all($opts)),
            'credit_notes_count' => $countAll(fn($opts) => $stripe->creditNotes->all($opts)),
            'payment_methods_count' => $paymentMethodsCount,
            'payment_link_line_items_count' => $paymentLinkLineItemsCount,
            'subscription_items_count' => $subscriptionItemsCount,
            // 拡張データ
            'coupons_count' => $countAll(fn($opts) => $stripe->coupons->all($opts)),
            'promotion_codes_count' => $countAll(fn($opts) => $stripe->promotionCodes->all($opts)),
            'setup_intents_count' => $countAll(fn($opts) => $stripe->setupIntents->all($opts)),
            'quotes_count' => $countAll(fn($opts) => $stripe->quotes->all($opts)),
            'subscription_schedules_count' => $countAll(fn($opts) => $stripe->subscriptionSchedules->all($opts)),
            'tax_rates_count' => $countAll(fn($opts) => $stripe->taxRates->all($opts)),
            'shipping_rates_count' => $countAll(fn($opts) => $stripe->shippingRates->all($opts)),
            'files_count' => $countAll(fn($opts) => $stripe->files->all($opts)),
            'country_specs_count' => $countAll(fn($opts) => $stripe->countrySpecs->all($opts)),
            // Issuing
            'issuing_cardholders_count' => $countAll(fn($opts) => $stripe->issuing->cardholders->all($opts)),
            'issuing_cards_count' => $countAll(fn($opts) => $stripe->issuing->cards->all($opts)),
            'issuing_authorizations_count' => $countAll(fn($opts) => $stripe->issuing->authorizations->all($opts)),
            'issuing_transactions_count' => $countAll(fn($opts) => $stripe->issuing->transactions->all($opts)),
            'issuing_disputes_count' => $countAll(fn($opts) => $stripe->issuing->disputes->all($opts)),
            // Terminal・Identity・Radar
            'terminal_locations_count' => $countAll(fn($opts) => $stripe->terminal->locations->all($opts)),
            'terminal_readers_count' => $countAll(fn($opts) => $stripe->terminal->readers->all($opts)),
            'verification_sessions_count' => $countAll(fn($opts) => $stripe->identity->verificationSessions->all($opts)),
            'verification_reports_count' => $countAll(fn($opts) => $stripe->identity->verificationReports->all($opts)),
            'radar_value_lists_count' => $countAll(fn($opts) => $stripe->radar->valueLists->all($opts)),
            // Financial Connections・Treasury
            'financial_connections_accounts_count' => $countAll(fn($opts) => $stripe->financialConnections->accounts->all($opts)),
            'treasury_financial_accounts_count' => $countAll(fn($opts) => $stripe->treasury->financialAccounts->all($opts)),
            // Sigma・Reporting
            'sigma_scheduled_query_runs_count' => $countAll(fn($opts) => $stripe->sigma->scheduledQueryRuns->all($opts)),
            'reporting_report_runs_count' => $countAll(fn($opts) => $stripe->reporting->reportRuns->all($opts)),
            'last_synced_at' => Carbon::now(),
        ];

        StripeDashboardStats::updateOrCreate(
            ['stripe_account_id' => $account->uuid],
            $statsData
        );

        // Stripeと通信したのでlast_connected_atも更新
        $account->last_connected_at = Carbon::now();
        $account->last_synced_at = Carbon::now();
        $account->save();

        // local_dbのカウントも含めて返す
        $statsData['local_db'] = $this->getLocalDbCounts($account->id);

        return $statsData;
    }

    public function refreshAllDashboardStats(): array
    {
        // 全アカウント処理のため、タイムアウトを無制限に設定
        set_time_limit(0);

        $accounts = StripeAccount::all();
        $results = [];

        foreach ($accounts as $account) {
            try {
                $results[] = $this->refreshDashboardStats($account->id);
            } catch (\Exception $e) {
                $results[] = [
                    'id' => $account->id,
                    'stripe_account_id' => $account->uuid,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
