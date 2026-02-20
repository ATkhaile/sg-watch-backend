<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeDispute extends BaseStripeModel
{
    protected $table = 'stripe_disputes';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'charge_id',
        'amount',
        'currency',
        'status',
        'evidence_details',
        'evidence_due_by',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'evidence_due_by' => 'datetime',
        'livemode' => 'boolean',
        'amount' => 'integer',
        'evidence_details' => 'array',
    ];

    public function charge(): BelongsTo
    {
        return $this->belongsTo(StripeCharge::class, 'charge_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
