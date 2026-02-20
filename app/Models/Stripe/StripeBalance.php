<?php

namespace App\Models\Stripe;

class StripeBalance extends BaseStripeModel
{
    protected $table = 'stripe_balances';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'currency',
        'available',
        'pending',
        'livemode',
        'status',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'available' => 'integer',
        'pending' => 'integer',
    ];
}
