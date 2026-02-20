<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeIssuingTransaction extends BaseStripeModel
{
    protected $table = 'stripe_issuing_transactions';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'card_id',
        'cardholder_id',
        'authorization_id',
        'amount',
        'currency',
        'type',
        'merchant_data',
        'dispute_id',
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
        'merchant_data' => 'array',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(StripeIssuingCard::class, 'card_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function cardholder(): BelongsTo
    {
        return $this->belongsTo(StripeIssuingCardholder::class, 'cardholder_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function authorization(): BelongsTo
    {
        return $this->belongsTo(StripeIssuingAuthorization::class, 'authorization_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function dispute(): BelongsTo
    {
        return $this->belongsTo(StripeIssuingDispute::class, 'dispute_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
