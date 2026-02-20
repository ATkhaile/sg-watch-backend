<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeCustomerBalanceTransaction extends BaseStripeModel
{
    protected $table = 'stripe_customer_balance_transactions';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'customer_id',
        'type',
        'amount',
        'currency',
        'ending_balance',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'amount' => 'integer',
        'ending_balance' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
