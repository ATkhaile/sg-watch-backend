<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeCheckoutSession extends BaseStripeModel
{
    protected $table = 'stripe_checkout_sessions';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'mode',
        'customer_id',
        'payment_intent_id',
        'url',
        'status',
        'amount_total',
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
        'amount_total' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function paymentIntent(): BelongsTo
    {
        return $this->belongsTo(StripePaymentIntent::class, 'payment_intent_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    /**
     * モードでフィルタ
     */
    public function scopeByMode($query, string $mode)
    {
        return $query->where('mode', $mode);
    }

    /**
     * ステータスでフィルタ
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * 完了したセッションのみ
     */
    public function scopeComplete($query)
    {
        return $query->where('status', 'complete');
    }
}
