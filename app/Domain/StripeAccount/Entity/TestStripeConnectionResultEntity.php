<?php

namespace App\Domain\StripeAccount\Entity;

class TestStripeConnectionResultEntity
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $accountId = null,
        public readonly ?string $country = null,
        public readonly ?string $defaultCurrency = null,
        public readonly ?bool $chargesEnabled = null,
        public readonly ?bool $payoutsEnabled = null,
        public readonly ?string $businessType = null,
        public readonly ?string $displayName = null,
        public readonly ?string $error = null,
        public readonly ?string $errorCode = null,
        public readonly ?bool $webhookSecretValid = null,
        public readonly ?string $webhookSecretError = null,
    ) {}

    public function toArray(): array
    {
        if (!$this->success) {
            return [
                'success' => false,
                'error' => $this->error,
                'error_code' => $this->errorCode,
            ];
        }

        $result = [
            'success' => true,
            'account_info' => [
                'id' => $this->accountId,
                'country' => $this->country,
                'default_currency' => $this->defaultCurrency,
                'charges_enabled' => $this->chargesEnabled,
                'charges_enabled_hint' => $this->chargesEnabled
                    ? null
                    : '決済を受け付けるには、Stripeダッシュボードで本人確認（KYC）を完了し、事業情報を入力する必要があります。',
                'payouts_enabled' => $this->payoutsEnabled,
                'payouts_enabled_hint' => $this->payoutsEnabled
                    ? null
                    : '出金を有効にするには、Stripeダッシュボードで銀行口座を登録し、本人確認を完了する必要があります。',
                'business_type' => $this->businessType,
                'display_name' => $this->displayName,
            ],
        ];

        if ($this->webhookSecretValid !== null) {
            $result['webhook_secret'] = [
                'valid' => $this->webhookSecretValid,
                'error' => $this->webhookSecretError,
            ];
        }

        return $result;
    }
}
