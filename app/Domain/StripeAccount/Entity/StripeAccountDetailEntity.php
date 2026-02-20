<?php

namespace App\Domain\StripeAccount\Entity;

class StripeAccountDetailEntity
{
    public function __construct(
        private ?int $id = null,
        private ?string $uuid = null,
        private ?string $displayName = null,
        private ?string $status = null,
        // Stripeから取得される値（nullを許容）
        private ?string $stripeId = null,
        private ?string $accountType = null,
        // parent_account_idはStripe Connect用。Stripeからは親の stripe_id が返される。
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
        private ?int $creatorId = null,
        private ?int $updaterId = null,
        private ?string $creatorName = null,
        private ?string $updaterName = null,
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
        private ?string $deletedAt = null,
        private int $statusCode = 0
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
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
            'last_connected_at' => $this->lastConnectedAt,
            'last_synced_at' => $this->lastSyncedAt,
            'creator_id' => $this->creatorId,
            'updater_id' => $this->updaterId,
            'creator_name' => $this->creatorName,
            'updater_name' => $this->updaterName,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

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

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    public function getUpdaterId(): ?int
    {
        return $this->updaterId;
    }

    public function getCreatorName(): ?string
    {
        return $this->creatorName;
    }

    public function getUpdaterName(): ?string
    {
        return $this->updaterName;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    // Setters
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setStripeId(?string $stripeId): self
    {
        $this->stripeId = $stripeId;
        return $this;
    }

    public function setAccountType(?string $accountType): self
    {
        $this->accountType = $accountType;
        return $this;
    }

    public function setParentAccountId(?int $parentAccountId): self
    {
        $this->parentAccountId = $parentAccountId;
        return $this;
    }

    public function setObjectType(?string $objectType): self
    {
        $this->objectType = $objectType;
        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setBusinessProfileName(?string $businessProfileName): self
    {
        $this->businessProfileName = $businessProfileName;
        return $this;
    }

    public function setBusinessProfileProductDescription(?string $businessProfileProductDescription): self
    {
        $this->businessProfileProductDescription = $businessProfileProductDescription;
        return $this;
    }

    public function setBusinessType(?string $businessType): self
    {
        $this->businessType = $businessType;
        return $this;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function setPayoutSettings(?array $payoutSettings): self
    {
        $this->payoutSettings = $payoutSettings;
        return $this;
    }

    public function setRequirementsCurrentlyDue(?array $requirementsCurrentlyDue): self
    {
        $this->requirementsCurrentlyDue = $requirementsCurrentlyDue;
        return $this;
    }

    public function setChargesEnabled(?bool $chargesEnabled): self
    {
        $this->chargesEnabled = $chargesEnabled;
        return $this;
    }

    public function setPayoutsEnabled(?bool $payoutsEnabled): self
    {
        $this->payoutsEnabled = $payoutsEnabled;
        return $this;
    }

    public function setPublicKey(?string $publicKey): self
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    public function setSecretKey(?string $secretKey): self
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    public function setWebhookSecret(?string $webhookSecret): self
    {
        $this->webhookSecret = $webhookSecret;
        return $this;
    }

    public function setIsTestMode(?bool $isTestMode): self
    {
        $this->isTestMode = $isTestMode;
        return $this;
    }

    public function setStripeCreated(?string $stripeCreated): self
    {
        $this->stripeCreated = $stripeCreated;
        return $this;
    }

    public function setLastConnectedAt(?string $lastConnectedAt): self
    {
        $this->lastConnectedAt = $lastConnectedAt;
        return $this;
    }

    public function setLastSyncedAt(?string $lastSyncedAt): self
    {
        $this->lastSyncedAt = $lastSyncedAt;
        return $this;
    }

    public function setCreatorId(?int $creatorId): self
    {
        $this->creatorId = $creatorId;
        return $this;
    }

    public function setUpdaterId(?int $updaterId): self
    {
        $this->updaterId = $updaterId;
        return $this;
    }

    public function setCreatorName(?string $creatorName): self
    {
        $this->creatorName = $creatorName;
        return $this;
    }

    public function setUpdaterName(?string $updaterName): self
    {
        $this->updaterName = $updaterName;
        return $this;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setDeletedAt(?string $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
