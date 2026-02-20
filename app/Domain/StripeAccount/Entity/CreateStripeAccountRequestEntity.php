<?php

namespace App\Domain\StripeAccount\Entity;

class CreateStripeAccountRequestEntity
{
    public function __construct(
        public readonly string $displayName,
        public readonly ?string $status = 'active',
        // Stripeから取得される値（nullを許容）
        public readonly ?string $stripeId = null,
        public readonly ?string $accountType = null,
        // parent_account_idはStripe Connect用。Stripeからは親の stripe_id が返される。
        // 保存時に stripe_id から parent_account_id (内部ID) に変換する必要がある。
        public readonly ?int $parentAccountId = null,
        public readonly ?string $objectType = null,
        public readonly ?string $email = null,
        public readonly ?string $businessProfileName = null,
        public readonly ?string $businessProfileProductDescription = null,
        public readonly ?string $businessType = null,
        public readonly ?string $country = null,
        public readonly ?string $currency = null,
        public readonly ?array $payoutSettings = null,
        public readonly ?array $requirementsCurrentlyDue = null,
        public readonly ?bool $chargesEnabled = null,
        public readonly ?bool $payoutsEnabled = null,
        public readonly ?string $publicKey = null,
        public readonly ?string $secretKey = null,
        public readonly ?string $webhookSecret = null,
        public readonly ?bool $isTestMode = null,
        public readonly ?string $stripeCreated = null,
        public readonly ?int $creatorId = null,
    ) {}

    public function toArray(): array
    {
        return [
            'display_name' => $this->displayName,
            'status' => $this->status,
            'stripe_id' => $this->stripeId,
            'account_type' => $this->accountType,
            'parent_account_id' => $this->parentAccountId,
            'object_type' => $this->objectType,
            'email' => $this->email,
            'business_profile_name' => $this->businessProfileName,
            'business_profile_product_description' => $this->businessProfileProductDescription,
            'business_type' => $this->businessType,
            'country' => $this->country,
            'currency' => $this->currency,
            'payout_settings' => $this->payoutSettings,
            'requirements_currently_due' => $this->requirementsCurrentlyDue,
            'charges_enabled' => $this->chargesEnabled,
            'payouts_enabled' => $this->payoutsEnabled,
            'public_key' => $this->publicKey,
            'secret_key' => $this->secretKey,
            'webhook_secret' => $this->webhookSecret,
            'is_test_mode' => $this->isTestMode,
            'stripe_created' => $this->stripeCreated,
            'creator_id' => $this->creatorId,
        ];
    }
}
