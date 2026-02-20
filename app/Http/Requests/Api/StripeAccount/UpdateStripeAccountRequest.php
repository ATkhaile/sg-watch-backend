<?php

namespace App\Http\Requests\Api\StripeAccount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStripeAccountRequest extends FormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'display_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
            // Stripeから取得される値（nullを許容）
            'stripe_id' => 'nullable|string|max:255',
            'account_type' => 'nullable|string|in:standard,express,custom',
            'parent_account_id' => 'nullable|integer|exists:stripe_accounts,id',
            'object_type' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'business_profile_name' => 'nullable|string|max:255',
            'business_profile_product_description' => 'nullable|string',
            'business_type' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:2',
            'currency' => 'nullable|string|max:3',
            'payout_settings' => 'nullable|array',
            'requirements_currently_due' => 'nullable|array',
            'charges_enabled' => 'nullable|boolean',
            'payouts_enabled' => 'nullable|boolean',
            'public_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'webhook_secret' => 'nullable|string',
            'is_test_mode' => 'nullable|boolean',
            'stripe_created' => 'nullable|date',
            'last_connected_at' => ['nullable', function ($attribute, $value, $fail) {
                if ($value !== 'now' && !strtotime($value)) {
                    $fail('The '.$attribute.' must be a valid date or "now".');
                }
            }],
            'last_synced_at' => ['nullable', function ($attribute, $value, $fail) {
                if ($value !== 'now' && !strtotime($value)) {
                    $fail('The '.$attribute.' must be a valid date or "now".');
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'display_name.string' => __('stripe_account.validation.display_name.string'),
            'display_name.max' => __('stripe_account.validation.display_name.max'),
            'public_key.string' => __('stripe_account.validation.public_key.string'),
            'secret_key.string' => __('stripe_account.validation.secret_key.string'),
            'status.in' => __('stripe_account.validation.status.in'),
        ];
    }
}
