<?php

namespace App\Models\Stripe;

class StripeFile extends BaseStripeModel
{
    protected $table = 'stripe_files';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'purpose',
        'filename',
        'size',
        'file_type',
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
        'size' => 'integer',
    ];
}
