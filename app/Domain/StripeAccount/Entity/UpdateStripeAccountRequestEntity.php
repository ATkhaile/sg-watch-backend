<?php

namespace App\Domain\StripeAccount\Entity;

class UpdateStripeAccountRequestEntity
{
    public function __construct(
        private ?string $displayName = null,
        private ?string $status = null,
        // Stripeから取得される値（nullを許容）
        private ?string $stripeId = null,
        private ?string $accountType = null,
        // parent_account_idはStripe Connect用。Stripeからは親の stripe_id が返される。
        // 保存時に stripe_id から parent_account_id (内部ID) に変換する必要がある。
        private ?int $parentAccountId = null,
        private ?string $objectType = null,
        private ?string $email = null,
        private ?string $businessProfileName = null,
        private ?string $businessProfileProductDescription = null,
        private ?string $businessType = null,
        private ?string $country = null,
        private ?string $currency = null,
        private ?array $payoutSettings = null,
        private ?array $requirementsCurrentlyDue = null,
        private ?bool $chargesEnabled = null,
        private ?bool $payoutsEnabled = null,
        private ?string $publicKey = null,
        private ?string $secretKey = null,
        private ?string $webhookSecret = null,
        private ?bool $isTestMode = null,
        private ?string $stripeCreated = null,
        private ?string $lastConnectedAt = null,
        private ?string $lastSyncedAt = null,
        private ?int $updaterId = null,
    ) {}

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function getAccountType(): ?string
    {
        return $this->accountType;
    }

    public function getParentAccountId(): ?int
    {
        return $this->parentAccountId;
    }

    public function getObjectType(): ?string
    {
        return $this->objectType;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getBusinessProfileName(): ?string
    {
        return $this->businessProfileName;
    }

    public function getBusinessProfileProductDescription(): ?string
    {
        return $this->businessProfileProductDescription;
    }

    public function getBusinessType(): ?string
    {
        return $this->businessType;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getPayoutSettings(): ?array
    {
        return $this->payoutSettings;
    }

    public function getRequirementsCurrentlyDue(): ?array
    {
        return $this->requirementsCurrentlyDue;
    }

    public function getChargesEnabled(): ?bool
    {
        return $this->chargesEnabled;
    }

    public function getPayoutsEnabled(): ?bool
    {
        return $this->payoutsEnabled;
    }

    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function getWebhookSecret(): ?string
    {
        return $this->webhookSecret;
    }

    public function getIsTestMode(): ?bool
    {
        return $this->isTestMode;
    }

    public function getStripeCreated(): ?string
    {
        return $this->stripeCreated;
    }

    public function getLastConnectedAt(): ?string
    {
        return $this->lastConnectedAt;
    }

    public function getLastSyncedAt(): ?string
    {
        return $this->lastSyncedAt;
    }

    public function getUpdaterId(): ?int
    {
        return $this->updaterId;
    }
}
