<?php

namespace App\Domain\StripeAccount\Infrastructure;

use App\Domain\StripeAccount\Entity\StripeAccountEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Domain\StripeAccount\Entity\StripeAccountDetailEntity;
use App\Domain\StripeAccount\Entity\CreateStripeAccountRequestEntity;
use App\Domain\StripeAccount\Entity\UpdateStripeAccountRequestEntity;
use App\Domain\StripeAccount\Entity\DeleteStripeAccountRequestEntity;
use App\Domain\StripeAccount\Entity\CustomerSubscriptionEntity;
use App\Domain\StripeAccount\Entity\GetStripeProductsRequestEntity;
use App\Domain\StripeAccount\Entity\StripeProductEntity;
use App\Domain\StripeAccount\Entity\GetStripePricesRequestEntity;
use App\Domain\StripeAccount\Entity\StripePriceEntity;
use App\Domain\StripeAccount\Entity\GetStripePaymentLinksRequestEntity;
use App\Domain\StripeAccount\Entity\StripePaymentLinkEntity;
use App\Domain\StripeAccount\Entity\GetStripeTransactionsRequestEntity;
use App\Domain\StripeAccount\Entity\StripeTransactionEntity;
use App\Models\Stripe\StripeAccount;
use App\Models\ActivationCode;
use App\Models\User;
use App\Enums\StatusCode;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DbStripeAccountInfrastructure implements StripeAccountRepository
{
    private StripeAccount $stripeAccount;

    public function __construct(StripeAccount $stripeAccount)
    {
        $this->stripeAccount = $stripeAccount;
    }

    public function getAllStripeAccount(StripeAccountEntity $requestEntity): LengthAwarePaginator
    {
        $query = StripeAccount::query();

        #Search
        if ($searchName = $requestEntity->getSearchName()) {
            $operatorName = $requestEntity->getSearchNameLike() ? 'LIKE' : '=';
            $searchValueName = $requestEntity->getSearchNameLike() ? "%{$searchName}%" : $searchName;

            if ($requestEntity->getSearchNameNot()) {
                $query->where('display_name', '!=', $searchName);
            } else {
                $query->where('display_name', $operatorName, $searchValueName);
            }
        }

        #Filter by status
        if ($filterStatus = $requestEntity->getFilterStatus()) {
            $query->where('status', $filterStatus);
        }

        #Sort
        $sortOrder = $requestEntity->getSortOrder();
        if ($sortOrder && !empty($sortOrder)) {
            foreach ($sortOrder as $param) {
                switch ($param) {
                    case 'sort_name':
                        if ($sortName = $requestEntity->getSortName()) {
                            $query->orderBy('display_name', $sortName);
                        }
                        break;
                    case 'sort_created':
                        if ($sortCreated = $requestEntity->getSortCreated()) {
                            $query->orderBy('created_at', $sortCreated);
                        }
                        break;
                    case 'sort_updated':
                        if ($sortUpdated = $requestEntity->getSortUpdated()) {
                            $query->orderBy('updated_at', $sortUpdated);
                        }
                        break;
                }
            }
        }

        return $query->paginate(
            $requestEntity->getLimit() ?? 10,
            ['*'],
            'page',
            $requestEntity->getPage() ?? 1
        );
    }

    public function getStripeAccountDetail(int $id): ?StripeAccountDetailEntity
    {
        $stripeAccount = StripeAccount::with(['creator', 'updater'])->where('id', $id)->first();

        if (!$stripeAccount) {
            return null;
        }

        return new StripeAccountDetailEntity(
            id: $stripeAccount->id,
            uuid: $stripeAccount->uuid,
            displayName: $stripeAccount->display_name,
            status: $stripeAccount->status,
            stripeId: $stripeAccount->stripe_id,
            accountType: $stripeAccount->account_type,
            parentAccountId: $stripeAccount->parent_account_id,
            objectType: $stripeAccount->object_type,
            email: $stripeAccount->email,
            businessProfileName: $stripeAccount->business_profile_name,
            businessProfileProductDescription: $stripeAccount->business_profile_product_description,
            businessType: $stripeAccount->business_type,
            country: $stripeAccount->country,
            currency: $stripeAccount->currency,
            payoutSettings: $stripeAccount->payout_settings,
            requirementsCurrentlyDue: $stripeAccount->requirements_currently_due,
            chargesEnabled: $stripeAccount->charges_enabled,
            payoutsEnabled: $stripeAccount->payouts_enabled,
            publicKey: $stripeAccount->public_key,
            secretKey: $stripeAccount->secret_key,
            webhookSecret: $stripeAccount->webhook_secret,
            isTestMode: $stripeAccount->is_test_mode,
            stripeCreated: $stripeAccount->stripe_created,
            lastConnectedAt: $stripeAccount->last_connected_at,
            lastSyncedAt: $stripeAccount->last_synced_at,
            creatorId: $stripeAccount->creator_id,
            updaterId: $stripeAccount->updater_id,
            creatorName: $stripeAccount->creator?->name,
            updaterName: $stripeAccount->updater?->name,
            createdAt: $stripeAccount->created_at,
            updatedAt: $stripeAccount->updated_at,
            statusCode: StatusCode::OK
        );
    }

    public function store(CreateStripeAccountRequestEntity $requestEntity): bool
    {
        try {
            $stripeAccount = new StripeAccount;
            $stripeAccount->display_name = $requestEntity->displayName;
            $stripeAccount->status = $requestEntity->status ?? 'active';
            $stripeAccount->stripe_id = $requestEntity->stripeId;
            $stripeAccount->account_type = $requestEntity->accountType;
            // parent_account_idはStripe Connect用。Stripeからは親の stripe_id が返される。
            $stripeAccount->parent_account_id = $requestEntity->parentAccountId;
            $stripeAccount->object_type = $requestEntity->objectType;
            $stripeAccount->email = $requestEntity->email;
            $stripeAccount->business_profile_name = $requestEntity->businessProfileName;
            $stripeAccount->business_profile_product_description = $requestEntity->businessProfileProductDescription;
            $stripeAccount->business_type = $requestEntity->businessType;
            $stripeAccount->country = $requestEntity->country;
            $stripeAccount->currency = $requestEntity->currency;
            $stripeAccount->payout_settings = $requestEntity->payoutSettings;
            $stripeAccount->requirements_currently_due = $requestEntity->requirementsCurrentlyDue;
            $stripeAccount->charges_enabled = $requestEntity->chargesEnabled;
            $stripeAccount->payouts_enabled = $requestEntity->payoutsEnabled;
            $stripeAccount->public_key = $requestEntity->publicKey;
            $stripeAccount->secret_key = $requestEntity->secretKey;
            $stripeAccount->webhook_secret = $requestEntity->webhookSecret;
            $stripeAccount->is_test_mode = $requestEntity->isTestMode;
            $stripeAccount->stripe_created = $requestEntity->stripeCreated;
            $stripeAccount->creator_id = $requestEntity->creatorId;
            $stripeAccount->save();
            return true;
        } catch (\Exception $e) {
            \Log::error('StripeAccount store error: ' . $e->getMessage());
            \Log::error('StripeAccount store trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function update(UpdateStripeAccountRequestEntity $requestEntity, int $id): bool
    {
        \Log::info('StripeAccount update called', ['id' => $id]);
        $stripeAccount = $this->findById($id);
        if (!$stripeAccount) {
            \Log::error('StripeAccount not found', ['id' => $id]);
            return false;
        }
        \Log::info('StripeAccount found', ['id' => $id]);

        DB::beginTransaction();
        try {
            if ($requestEntity->getDisplayName() !== null) {
                $stripeAccount->display_name = $requestEntity->getDisplayName();
            }

            if ($requestEntity->getStatus() !== null) {
                $stripeAccount->status = $requestEntity->getStatus();
            }

            if ($requestEntity->getStripeId() !== null) {
                $stripeAccount->stripe_id = $requestEntity->getStripeId();
            }

            if ($requestEntity->getAccountType() !== null) {
                $stripeAccount->account_type = $requestEntity->getAccountType();
            }

            // parent_account_idはStripe Connect用。Stripeからは親の stripe_id が返される。
            if ($requestEntity->getParentAccountId() !== null) {
                $stripeAccount->parent_account_id = $requestEntity->getParentAccountId();
            }

            if ($requestEntity->getObjectType() !== null) {
                $stripeAccount->object_type = $requestEntity->getObjectType();
            }

            if ($requestEntity->getEmail() !== null) {
                $stripeAccount->email = $requestEntity->getEmail();
            }

            if ($requestEntity->getBusinessProfileName() !== null) {
                $stripeAccount->business_profile_name = $requestEntity->getBusinessProfileName();
            }

            if ($requestEntity->getBusinessProfileProductDescription() !== null) {
                $stripeAccount->business_profile_product_description = $requestEntity->getBusinessProfileProductDescription();
            }

            if ($requestEntity->getBusinessType() !== null) {
                $stripeAccount->business_type = $requestEntity->getBusinessType();
            }

            if ($requestEntity->getCountry() !== null) {
                $stripeAccount->country = $requestEntity->getCountry();
            }

            if ($requestEntity->getCurrency() !== null) {
                $stripeAccount->currency = $requestEntity->getCurrency();
            }

            if ($requestEntity->getPayoutSettings() !== null) {
                $stripeAccount->payout_settings = $requestEntity->getPayoutSettings();
            }

            if ($requestEntity->getRequirementsCurrentlyDue() !== null) {
                $stripeAccount->requirements_currently_due = $requestEntity->getRequirementsCurrentlyDue();
            }

            if ($requestEntity->getChargesEnabled() !== null) {
                $stripeAccount->charges_enabled = $requestEntity->getChargesEnabled();
            }

            if ($requestEntity->getPayoutsEnabled() !== null) {
                $stripeAccount->payouts_enabled = $requestEntity->getPayoutsEnabled();
            }

            if ($requestEntity->getPublicKey() !== null) {
                $stripeAccount->public_key = $requestEntity->getPublicKey();
            }

            if ($requestEntity->getSecretKey() !== null) {
                $stripeAccount->secret_key = $requestEntity->getSecretKey();
            }

            if ($requestEntity->getWebhookSecret() !== null) {
                $stripeAccount->webhook_secret = $requestEntity->getWebhookSecret();
            }

            if ($requestEntity->getIsTestMode() !== null) {
                $stripeAccount->is_test_mode = $requestEntity->getIsTestMode();
            }

            if ($requestEntity->getStripeCreated() !== null) {
                $stripeAccount->stripe_created = $requestEntity->getStripeCreated();
            }

            if ($requestEntity->getLastConnectedAt() !== null) {
                // "now"が渡された場合はサーバー時間を使用
                $stripeAccount->last_connected_at = $requestEntity->getLastConnectedAt() === 'now'
                    ? \Carbon\Carbon::now()
                    : $requestEntity->getLastConnectedAt();
            }

            if ($requestEntity->getLastSyncedAt() !== null) {
                // "now"が渡された場合はサーバー時間を使用
                $stripeAccount->last_synced_at = $requestEntity->getLastSyncedAt() === 'now'
                    ? \Carbon\Carbon::now()
                    : $requestEntity->getLastSyncedAt();
            }

            if ($requestEntity->getUpdaterId() !== null) {
                $stripeAccount->updater_id = $requestEntity->getUpdaterId();
            }

            if (!$stripeAccount->save()) {
                DB::rollBack();
                return false;
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            \Log::error('StripeAccount update error: ' . $e->getMessage());
            \Log::error('StripeAccount update trace: ' . $e->getTraceAsString());
            DB::rollBack();
            return false;
        }
    }

    public function delete(DeleteStripeAccountRequestEntity $requestEntity): bool
    {
        $stripeAccount = $this->findById($requestEntity->getId());
        if (!$stripeAccount) {
            return false;
        }

        return $stripeAccount->delete();
    }

    public function getUserActiveStripeAccountId(int $userId): ?string
    {
        return ActivationCode::where('user_id', $userId)
            ->where('is_used', true)
            ->latest('activated_at')
            ->value('stripe_account_id');
    }

    public function getAdminCustomerSubscriptions(CustomerSubscriptionEntity $entity, int $adminUserId): LengthAwarePaginator
    {
        // PlanLegacy and UserStripeCustomer tables have been removed
        // This feature was incomplete and unused
        // Return empty paginator
        return new LengthAwarePaginator(
            [],
            0,
            $entity->getLimit() ?? 10,
            $entity->getPage() ?? 1
        );
    }

    private function findById(int $id): ?StripeAccount
    {
        return StripeAccount::where('id', $id)
            ->whereNull('deleted_at')
            ->first();
    }

    public function getStripeProducts(GetStripeProductsRequestEntity $entity): StripeProductEntity
    {
        $stripeAccount = $this->getStripeAccountDetail($entity->getStripeAccountId());

        if (!$stripeAccount) {
            return new StripeProductEntity([], null, null, 404);
        }

        try {
            \Stripe\Stripe::setApiKey($stripeAccount->getSecretKey());

            $params = [];

            if ($entity->getLimit()) {
                $params['limit'] = $entity->getLimit();
            }

            if ($entity->getStartingAfter()) {
                $params['starting_after'] = $entity->getStartingAfter();
            }

            if ($entity->getEndingBefore()) {
                $params['ending_before'] = $entity->getEndingBefore();
            }

            if ($entity->getActive() !== null) {
                $params['active'] = $entity->getActive();
            }

            $products = \Stripe\Product::all($params);

            $productData = [];
            foreach ($products->data as $product) {
                $productData[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'active' => $product->active,
                    'created' => $product->created,
                    'updated' => $product->updated,
                    'images' => $product->images,
                    'metadata' => $product->metadata,
                    'default_price' => $product->default_price,
                ];
            }

            return new StripeProductEntity(
                $productData,
                $products->has_more ? 'true' : 'false',
                $products->has_more && !empty($products->data) ? end($products->data)->id : null,
                200
            );
        } catch (\Exception $e) {
            return new StripeProductEntity([], null, null, 500);
        }
    }

    public function getStripePrices(GetStripePricesRequestEntity $entity): StripePriceEntity
    {
        $stripeAccount = $this->getStripeAccountDetail($entity->getStripeAccountId());

        if (!$stripeAccount) {
            return new StripePriceEntity([], null, null, 404);
        }

        try {
            \Stripe\Stripe::setApiKey($stripeAccount->getSecretKey());

            $params = [];

            if ($entity->getProductId()) {
                $params['product'] = $entity->getProductId();
            }

            if ($entity->getLimit()) {
                $params['limit'] = $entity->getLimit();
            }

            if ($entity->getStartingAfter()) {
                $params['starting_after'] = $entity->getStartingAfter();
            }

            if ($entity->getEndingBefore()) {
                $params['ending_before'] = $entity->getEndingBefore();
            }

            if ($entity->getActive() !== null) {
                $params['active'] = $entity->getActive();
            }

            $prices = \Stripe\Price::all($params);

            $priceData = [];
            foreach ($prices->data as $price) {
                $priceData[] = [
                    'id' => $price->id,
                    'object' => $price->object,
                    'active' => $price->active,
                    'billing_scheme' => $price->billing_scheme,
                    'created' => $price->created,
                    'currency' => $price->currency,
                    'lookup_key' => $price->lookup_key,
                    'metadata' => $price->metadata,
                    'nickname' => $price->nickname,
                    'product' => $price->product,
                    'recurring' => $price->recurring,
                    'tax_behavior' => $price->tax_behavior,
                    'tiers_mode' => $price->tiers_mode,
                    'transform_quantity' => $price->transform_quantity,
                    'type' => $price->type,
                    'unit_amount' => $price->unit_amount,
                    'unit_amount_decimal' => $price->unit_amount_decimal,
                ];
            }

            return new StripePriceEntity(
                $priceData,
                $prices->has_more ? 'true' : 'false',
                $prices->has_more && !empty($prices->data) ? end($prices->data)->id : null,
                200
            );
        } catch (\Exception $e) {
            return new StripePriceEntity([], null, null, 500);
        }
    }

    public function getStripePaymentLinks(GetStripePaymentLinksRequestEntity $entity): StripePaymentLinkEntity
    {
        $stripeAccount = $this->getStripeAccountDetail($entity->getStripeAccountId());

        if (!$stripeAccount) {
            return new StripePaymentLinkEntity([], null, null, 404);
        }

        try {
            \Stripe\Stripe::setApiKey($stripeAccount->getSecretKey());

            $params = [];

            if ($entity->getLimit()) {
                $params['limit'] = $entity->getLimit();
            }

            if ($entity->getStartingAfter()) {
                $params['starting_after'] = $entity->getStartingAfter();
            }

            if ($entity->getEndingBefore()) {
                $params['ending_before'] = $entity->getEndingBefore();
            }

            if ($entity->getActive() !== null) {
                $params['active'] = $entity->getActive();
            }

            // Expand line_items to get price details
            $params['expand'] = ['data.line_items'];
            $paymentLinks = \Stripe\PaymentLink::all($params);

            $paymentLinkData = [];
            foreach ($paymentLinks->data as $link) {
                // Calculate total amount from line_items
                $totalAmount = 0;
                if (isset($link->line_items->data)) {
                    foreach ($link->line_items->data as $item) {
                        $totalAmount += $item->amount_total ?? 0;
                    }
                }

                $paymentLinkData[] = [
                    'id' => $link->id,
                    'object' => $link->object,
                    'active' => $link->active,
                    'url' => $link->url,
                    'metadata' => $link->metadata,
                    'line_items' => $link->line_items,
                    'total_amount' => $totalAmount,
                    'after_completion' => $link->after_completion,
                    'allow_promotion_codes' => $link->allow_promotion_codes,
                    'application_fee_amount' => $link->application_fee_amount,
                    'application_fee_percent' => $link->application_fee_percent,
                    'automatic_tax' => $link->automatic_tax,
                    'billing_address_collection' => $link->billing_address_collection,
                    'created' => $link->created,
                    'currency' => $link->currency,
                    'custom_fields' => $link->custom_fields,
                    'custom_text' => $link->custom_text,
                    'customer_creation' => $link->customer_creation,
                    'invoice_creation' => $link->invoice_creation,
                    'livemode' => $link->livemode,
                    'on_behalf_of' => $link->on_behalf_of,
                    'payment_intent_data' => $link->payment_intent_data,
                    'payment_method_collection' => $link->payment_method_collection,
                    'payment_method_types' => $link->payment_method_types,
                    'phone_number_collection' => $link->phone_number_collection,
                    'shipping_address_collection' => $link->shipping_address_collection,
                    'shipping_options' => $link->shipping_options,
                    'submit_type' => $link->submit_type,
                    'subscription_data' => $link->subscription_data,
                    'tax_id_collection' => $link->tax_id_collection,
                    'transfer_data' => $link->transfer_data,
                ];
            }

            return new StripePaymentLinkEntity(
                $paymentLinkData,
                $paymentLinks->has_more ? 'true' : 'false',
                $paymentLinks->has_more && !empty($paymentLinks->data) ? end($paymentLinks->data)->id : null,
                200,
                count($paymentLinkData)
            );
        } catch (\Exception $e) {
            return new StripePaymentLinkEntity([], null, null, 500, 0);
        }
    }

    public function getStripeTransactions(GetStripeTransactionsRequestEntity $entity): StripeTransactionEntity
    {
        $stripeAccount = $this->getStripeAccountDetail($entity->getStripeAccountId());

        if (!$stripeAccount) {
            return new StripeTransactionEntity([], false, null, 404);
        }

        try {
            \Stripe\Stripe::setApiKey($stripeAccount->getSecretKey());

            $params = [];

            if ($entity->getLimit()) {
                $params['limit'] = $entity->getLimit();
            }

            if ($entity->getStartingAfter()) {
                $params['starting_after'] = $entity->getStartingAfter();
            }

            if ($entity->getEndingBefore()) {
                $params['ending_before'] = $entity->getEndingBefore();
            }

            if ($entity->getCreated()) {
                $params['created'] = $entity->getCreated();
            }

            if ($entity->getStartDate() || $entity->getEndDate()) {
                $createdFilter = [];

                if ($entity->getStartDate()) {
                    $startTimestamp = Carbon::parse($entity->getStartDate())->startOfDay()->timestamp;
                    $createdFilter['gte'] = $startTimestamp;
                }

                if ($entity->getEndDate()) {
                    $endTimestamp = Carbon::parse($entity->getEndDate())->endOfDay()->timestamp;
                    $createdFilter['lte'] = $endTimestamp;
                }

                $params['created'] = $createdFilter;
            }

            $paymentIntents = \Stripe\PaymentIntent::all($params);

            $stripeAccountIdentifier = substr(md5($stripeAccount->getWebhookSecret()), 0, 16);

            $stripeCustomerIds = [];
            foreach ($paymentIntents->data as $intent) {
                if ($intent->customer) {
                    $stripeCustomerIds[] = $intent->customer;
                }
            }

            $customerMap = [];

            // UserStripeCustomer table removed - fetch user info directly from Stripe Customer
            if (!empty($stripeCustomerIds)) {
                foreach ($stripeCustomerIds as $customerId) {
                    try {
                        $stripeCustomer = \Stripe\Customer::retrieve($customerId);
                        if ($stripeCustomer->email) {
                            $user = User::where('email', $stripeCustomer->email)->first();
                            if ($user) {
                                $customerMap[$customerId] = [
                                    'user_id' => $user->id,
                                    'user_name' => $user->full_name,
                                    'user_email' => $user->email,
                                    'plan_name' => null, // Plan info no longer available from removed PlanLegacy
                                ];
                            }
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }

            $transactionData = [];
            foreach ($paymentIntents->data as $intent) {
                $userInfo = null;
                if ($intent->customer && isset($customerMap[$intent->customer])) {
                    $userInfo = $customerMap[$intent->customer];
                }

                $transactionData[] = [
                    'id' => $intent->id,
                    'object' => $intent->object,
                    'amount' => $intent->amount,
                    'amount_capturable' => $intent->amount_capturable,
                    'amount_received' => $intent->amount_received,
                    'application' => $intent->application,
                    'application_fee_amount' => $intent->application_fee_amount,
                    'canceled_at' => $intent->canceled_at,
                    'cancellation_reason' => $intent->cancellation_reason,
                    'capture_method' => $intent->capture_method,
                    'charges' => $intent->charges,
                    'client_secret' => $intent->client_secret,
                    'confirmation_method' => $intent->confirmation_method,
                    'created' => $intent->created,
                    'currency' => $intent->currency,
                    'customer' => $intent->customer,
                    'description' => $intent->description,
                    'invoice' => $intent->invoice,
                    'last_payment_error' => $intent->last_payment_error,
                    'livemode' => $intent->livemode,
                    'metadata' => $intent->metadata,
                    'payment_method' => $intent->payment_method,
                    'payment_method_types' => $intent->payment_method_types,
                    'receipt_email' => $intent->receipt_email,
                    'setup_future_usage' => $intent->setup_future_usage,
                    'shipping' => $intent->shipping,
                    'statement_descriptor' => $intent->statement_descriptor,
                    'status' => $intent->status,
                    'user_info' => $userInfo,
                ];
            }

            $today = Carbon::today();
            $todayStats = [
                'success' => 0,
                'failed' => 0,
                'total_amount' => 0,
            ];

            foreach ($transactionData as $transaction) {
                $transactionDate = Carbon::createFromTimestamp($transaction['created']);

                if ($transactionDate->isSameDay($today)) {
                    if ($transaction['status'] === 'succeeded') {
                        $todayStats['success']++;
                        $todayStats['total_amount'] += $transaction['amount_received'];
                    } elseif ($transaction['status'] === 'failed' || $transaction['status'] === 'canceled') {
                        $todayStats['failed']++;
                    }
                }
            }

            $result = new StripeTransactionEntity(
                $transactionData,
                $paymentIntents->has_more,
                $paymentIntents->has_more && !empty($paymentIntents->data) ? end($paymentIntents->data)->id : null,
                200,
                count($transactionData)
            );

            $result->setTodayStats($todayStats);

            return $result;
        } catch (\Exception $e) {
            return new StripeTransactionEntity([], false, null, 500, 0);
        }
    }

    public function getStripeTransactionsForExport(\App\Domain\StripeAccount\Entity\ExportStripeTransactionsRequestEntity $entity): array
    {
        $stripeAccount = $this->getStripeAccountDetail($entity->getStripeAccountId());

        if (!$stripeAccount) {
            return [];
        }

        try {
            \Stripe\Stripe::setApiKey($stripeAccount->getSecretKey());

            $params = [];

            if ($entity->getLimit()) {
                $params['limit'] = $entity->getLimit();
            }

            if ($entity->getStartDate() || $entity->getEndDate()) {
                $createdFilter = [];

                if ($entity->getStartDate()) {
                    $startTimestamp = Carbon::parse($entity->getStartDate())->startOfDay()->timestamp;
                    $createdFilter['gte'] = $startTimestamp;
                }

                if ($entity->getEndDate()) {
                    $endTimestamp = Carbon::parse($entity->getEndDate())->endOfDay()->timestamp;
                    $createdFilter['lte'] = $endTimestamp;
                }

                $params['created'] = $createdFilter;
            }

            $paymentIntents = \Stripe\PaymentIntent::all($params);

            $stripeAccountIdentifier = substr(md5($stripeAccount->getWebhookSecret()), 0, 16);

            $stripeCustomerIds = [];
            foreach ($paymentIntents->data as $intent) {
                if ($intent->customer) {
                    $stripeCustomerIds[] = $intent->customer;
                }
            }

            $customerMap = [];

            // UserStripeCustomer table removed - fetch user info directly from Stripe Customer
            if (!empty($stripeCustomerIds)) {
                foreach ($stripeCustomerIds as $customerId) {
                    try {
                        $stripeCustomer = \Stripe\Customer::retrieve($customerId);
                        if ($stripeCustomer->email) {
                            $user = User::where('email', $stripeCustomer->email)->first();
                            if ($user) {
                                $customerMap[$customerId] = [
                                    'user_id' => $user->id,
                                    'user_name' => $user->full_name,
                                    'user_email' => $user->email,
                                    'plan_name' => null, // Plan info no longer available from removed PlanLegacy
                                ];
                            }
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }

            $transactionData = [];
            foreach ($paymentIntents->data as $intent) {
                $userInfo = null;
                if ($intent->customer && isset($customerMap[$intent->customer])) {
                    $userInfo = $customerMap[$intent->customer];
                }

                $transactionData[] = [
                    'id' => $intent->id,
                    'object' => $intent->object,
                    'amount' => $intent->amount,
                    'amount_capturable' => $intent->amount_capturable,
                    'amount_received' => $intent->amount_received,
                    'application' => $intent->application,
                    'application_fee_amount' => $intent->application_fee_amount,
                    'canceled_at' => $intent->canceled_at,
                    'cancellation_reason' => $intent->cancellation_reason,
                    'capture_method' => $intent->capture_method,
                    'charges' => $intent->charges,
                    'client_secret' => $intent->client_secret,
                    'confirmation_method' => $intent->confirmation_method,
                    'created' => $intent->created,
                    'currency' => $intent->currency,
                    'customer' => $intent->customer,
                    'description' => $intent->description,
                    'invoice' => $intent->invoice,
                    'last_payment_error' => $intent->last_payment_error,
                    'livemode' => $intent->livemode,
                    'metadata' => $intent->metadata,
                    'payment_method' => $intent->payment_method,
                    'payment_method_types' => $intent->payment_method_types,
                    'receipt_email' => $intent->receipt_email,
                    'setup_future_usage' => $intent->setup_future_usage,
                    'shipping' => $intent->shipping,
                    'statement_descriptor' => $intent->statement_descriptor,
                    'status' => $intent->status,
                    'user_info' => $userInfo,
                ];
            }

            return $transactionData;
        } catch (\Exception $e) {
            return [];
        }
    }
}
