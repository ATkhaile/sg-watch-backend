<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeIssuingDispute extends BaseStripeModel
{
    protected $table = 'stripe_issuing_disputes';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'transaction_id',
        'amount',
        'currency',
        'reason',
        'status',
        'evidence',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'amount' => 'integer',
        'evidence' => 'array',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(StripeIssuingTransaction::class, 'transaction_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
