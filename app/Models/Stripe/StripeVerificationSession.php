<?php

namespace App\Models\Stripe;

class StripeVerificationSession extends BaseStripeModel
{
    protected $table = 'stripe_verification_sessions';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'status',
        'type',
        'client_secret',
        'options',
        'verified_outputs',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'options' => 'array',
        'verified_outputs' => 'array',
    ];

    protected $hidden = [
        'client_secret',
    ];
}
