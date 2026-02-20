<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeIssuingCard extends BaseStripeModel
{
    protected $table = 'stripe_issuing_cards';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'cardholder_id',
        'last4',
        'brand',
        'exp_month',
        'exp_year',
        'status',
        'type',
        'currency',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'exp_month' => 'integer',
        'exp_year' => 'integer',
    ];

    public function cardholder(): BelongsTo
    {
        return $this->belongsTo(StripeIssuingCardholder::class, 'cardholder_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function authorizations(): HasMany
    {
        return $this->hasMany(StripeIssuingAuthorization::class, 'card_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(StripeIssuingTransaction::class, 'card_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
