<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeTreasuryTransaction extends BaseStripeModel
{
    protected $table = 'stripe_treasury_transactions';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'financial_account_id',
        'amount',
        'currency',
        'type',
        'status',
        'description',
        'balance_impact',
        'flow_details',
        'flow_type',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'balance_impact' => 'array',
        'flow_details' => 'array',
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];

    public function financialAccount(): BelongsTo
    {
        return $this->belongsTo(StripeTreasuryFinancialAccount::class, 'financial_account_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
