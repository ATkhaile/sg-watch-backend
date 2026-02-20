<?php

namespace App\Models\Stripe;

class StripeTaxRate extends BaseStripeModel
{
    protected $table = 'stripe_tax_rates';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'display_name',
        'description',
        'jurisdiction',
        'percentage',
        'inclusive',
        'active',
        'country',
        'state',
        'tax_type',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'active' => 'boolean',
        'inclusive' => 'boolean',
        'percentage' => 'decimal:4',
    ];
}
