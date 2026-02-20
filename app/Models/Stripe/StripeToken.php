<?php

namespace App\Models\Stripe;

class StripeToken extends BaseStripeModel
{
    protected $table = 'stripe_tokens';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'type',
        'card_last4',
        'card_brand',
        'used',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'used' => 'boolean',
    ];
}
