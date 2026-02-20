<?php

namespace App\Models\Stripe;

class StripeCapability extends BaseStripeModel
{
    protected $table = 'stripe_capabilities';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'connected_account_id',
        'capability_name',
        'status',
        'requested',
        'requested_at',
        'disabled_reason',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'requested' => 'boolean',
    ];
}
