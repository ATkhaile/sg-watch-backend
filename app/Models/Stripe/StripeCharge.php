<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeCharge extends BaseStripeModel
{
    protected $table = 'stripe_charges';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'amount',
        'currency',
        'customer_id',
        'invoice_id',
        'payment_intent_id',
        'description',
        'status',
        'paid',
        'refunded',
        'failure_code',
        'failure_message',
        'captured',
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
        'paid' => 'boolean',
        'refunded' => 'boolean',
        'captured' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(StripeInvoice::class, 'invoice_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function paymentIntent(): BelongsTo
    {
        return $this->belongsTo(StripePaymentIntent::class, 'payment_intent_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(StripeRefund::class, 'charge_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    /**
     * 支払い済みのみ
     */
    public function scopePaid($query)
    {
        return $query->where('paid', true);
    }

    /**
     * 返金済みのみ
     */
    public function scopeRefunded($query)
    {
        return $query->where('refunded', true);
    }

    /**
     * 失敗した請求のみ
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
