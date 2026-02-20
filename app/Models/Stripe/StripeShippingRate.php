<?php

namespace App\Models\Stripe;

class StripeShippingRate extends BaseStripeModel
{
    protected $table = 'stripe_shipping_rates';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'display_name',
        'type',
        'fixed_amount',
        'currency',
        'delivery_estimate',
        'active',
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
        'fixed_amount' => 'integer',
        'delivery_estimate' => 'array',
    ];
}
