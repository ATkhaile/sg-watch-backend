<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeInvoiceItem extends BaseStripeModel
{
    protected $table = 'stripe_invoice_items';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'invoice_id',
        'customer_id',
        'price_id',
        'quantity',
        'amount',
        'currency',
        'description',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'quantity' => 'integer',
        'amount' => 'integer',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(StripeInvoice::class, 'invoice_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function price(): BelongsTo
    {
        return $this->belongsTo(StripePrice::class, 'price_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
