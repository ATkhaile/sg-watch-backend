<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeQuote extends BaseStripeModel
{
    protected $table = 'stripe_quotes';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'customer_id',
        'status',
        'amount_subtotal',
        'amount_total',
        'currency',
        'expires_at',
        'subscription_id',
        'invoice_id',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'expires_at' => 'datetime',
        'livemode' => 'boolean',
        'amount_subtotal' => 'integer',
        'amount_total' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(StripeSubscription::class, 'subscription_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(StripeInvoice::class, 'invoice_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
