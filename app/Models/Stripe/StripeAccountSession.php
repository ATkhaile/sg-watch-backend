<?php

namespace App\Models\Stripe;

class StripeAccountSession extends BaseStripeModel
{
    protected $table = 'stripe_account_sessions';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'account_id',
        'client_secret',
        'components',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'components' => 'array',
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];
}
