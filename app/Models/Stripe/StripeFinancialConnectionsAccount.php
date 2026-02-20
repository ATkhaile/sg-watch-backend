<?php

namespace App\Models\Stripe;

class StripeFinancialConnectionsAccount extends BaseStripeModel
{
    protected $table = 'stripe_financial_connections_accounts';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'institution_name',
        'category',
        'subcategory',
        'status',
        'balance',
        'currency',
        'last4',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'balance' => 'array',
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];
}
