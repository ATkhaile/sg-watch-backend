<?php

namespace App\Models\Stripe;

class StripeFinancialConnectionsSession extends BaseStripeModel
{
    protected $table = 'stripe_financial_connections_sessions';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'account_holder',
        'accounts',
        'filters',
        'permissions',
        'client_secret',
        'return_url',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'account_holder' => 'array',
        'accounts' => 'array',
        'filters' => 'array',
        'permissions' => 'array',
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];
}
