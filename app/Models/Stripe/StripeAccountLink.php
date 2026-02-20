<?php

namespace App\Models\Stripe;

class StripeAccountLink extends BaseStripeModel
{
    protected $table = 'stripe_account_links';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'connected_account_id',
        'type',
        'url',
        'expires_at',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
