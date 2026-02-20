<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\UpdateStripeAccountRequestEntity;
use App\Http\Requests\Api\StripeAccount\UpdateStripeAccountRequest;

class UpdateStripeAccountRequestFactory
{
    public function createFromRequest(UpdateStripeAccountRequest $request): UpdateStripeAccountRequestEntity
    {
        $publicKey = $request->input('public_key');
        // public_keyからテストモードを判定（public_keyが指定されている場合のみ）
        $isTestMode = $publicKey ? str_starts_with($publicKey, 'pk_test_') : null;

        return new UpdateStripeAccountRequestEntity(
            displayName: $request->input('display_name'),
            status: $request->input('status'),
            // Stripeから取得される値
            stripeId: $request->input('stripe_id'),
            accountType: $request->input('account_type'),
            // parent_account_idはStripe Connect用
            parentAccountId: $request->input('parent_account_id'),
            objectType: $request->input('object_type'),
            email: $request->input('email'),
            businessProfileName: $request->input('business_profile_name'),
            businessProfileProductDescription: $request->input('business_profile_product_description'),
            businessType: $request->input('business_type'),
            country: $request->input('country'),
            currency: $request->input('currency'),
            payoutSettings: $request->input('payout_settings'),
            requirementsCurrentlyDue: $request->input('requirements_currently_due'),
            chargesEnabled: $request->has('charges_enabled') ? $request->boolean('charges_enabled') : null,
            payoutsEnabled: $request->has('payouts_enabled') ? $request->boolean('payouts_enabled') : null,
            publicKey: $publicKey,
            secretKey: $request->input('secret_key'),
            webhookSecret: $request->input('webhook_secret'),
            isTestMode: $isTestMode,
            stripeCreated: $request->input('stripe_created'),
            lastConnectedAt: $request->input('last_connected_at'),
            lastSyncedAt: $request->input('last_synced_at'),
            updaterId: (int) auth()->user()->id,
        );
    }
}
