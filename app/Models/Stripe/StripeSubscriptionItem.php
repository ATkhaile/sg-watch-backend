<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeSubscriptionItem extends BaseStripeModel
{
    protected $table = 'stripe_subscription_items';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'subscription_id',
        'price_id',
        'quantity',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'quantity' => 'integer',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(StripeSubscription::class, 'subscription_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function price(): BelongsTo
    {
        return $this->belongsTo(StripePrice::class, 'price_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
