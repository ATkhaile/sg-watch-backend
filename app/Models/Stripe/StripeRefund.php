<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeRefund extends BaseStripeModel
{
    protected $table = 'stripe_refunds';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'charge_id',
        'payment_intent_id',
        'amount',
        'currency',
        'status',
        'reason',
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
    ];

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

    /**
     * ステータスでフィルタ
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * 成功した返金のみ
     */
    public function scopeSucceeded($query)
    {
        return $query->where('status', 'succeeded');
    }
}
