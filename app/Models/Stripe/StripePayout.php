<?php

namespace App\Models\Stripe;

class StripePayout extends BaseStripeModel
{
    protected $table = 'stripe_payouts';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'amount',
        'currency',
        'arrival_date',
        'status',
        'method',
        'type',
        'failure_code',
        'failure_message',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'arrival_date' => 'datetime',
        'livemode' => 'boolean',
        'amount' => 'integer',
    ];

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
