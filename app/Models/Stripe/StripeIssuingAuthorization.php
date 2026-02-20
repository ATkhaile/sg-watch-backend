<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeIssuingAuthorization extends BaseStripeModel
{
    protected $table = 'stripe_issuing_authorizations';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'card_id',
        'cardholder_id',
        'amount',
        'currency',
        'status',
        'approved',
        'merchant_data',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'approved' => 'boolean',
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
}
