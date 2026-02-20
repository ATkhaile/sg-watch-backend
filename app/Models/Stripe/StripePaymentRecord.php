<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripePaymentRecord extends BaseStripeModel
{
    protected $table = 'stripe_payment_records';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'charge_id',
        'payment_intent_id',
        'invoice_id',
        'amount',
        'amount_canceled',
        'amount_failed',
        'amount_guaranteed',
        'amount_requested',
        'currency',
        'customer_id',
        'customer_details',
        'payment_method_id',
        'payment_method_details',
        'payment_reference',
        'shipping_details',
        'status',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'customer_details' => 'array',
        'payment_method_details' => 'array',
        'shipping_details' => 'array',
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(StripeCharge::class, 'charge_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function paymentIntent(): BelongsTo
    {
        return $this->belongsTo(StripePaymentIntent::class, 'payment_intent_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(StripeInvoice::class, 'invoice_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
