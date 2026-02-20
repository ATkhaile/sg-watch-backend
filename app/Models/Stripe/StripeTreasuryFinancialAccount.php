<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeTreasuryFinancialAccount extends BaseStripeModel
{
    protected $table = 'stripe_treasury_financial_accounts';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'supported_currencies',
        'balance',
        'country',
        'status',
        'status_details',
        'active_features',
        'pending_features',
        'restricted_features',
        'financial_addresses',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'supported_currencies' => 'array',
        'balance' => 'array',
        'status_details' => 'array',
        'active_features' => 'array',
        'pending_features' => 'array',
        'restricted_features' => 'array',
        'financial_addresses' => 'array',
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(StripeTreasuryTransaction::class, 'financial_account_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
