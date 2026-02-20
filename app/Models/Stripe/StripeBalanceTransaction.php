<?php

namespace App\Models\Stripe;

class StripeBalanceTransaction extends BaseStripeModel
{
    protected $table = 'stripe_balance_transactions';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'amount',
        'currency',
        'type',
        'net',
        'fee',
        'source_id',
        'description',
        'status',
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
        'net' => 'integer',
        'fee' => 'integer',
    ];
}
