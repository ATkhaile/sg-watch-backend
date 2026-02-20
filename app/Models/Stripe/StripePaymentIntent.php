<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StripePaymentIntent extends BaseStripeModel
{
    protected $table = 'stripe_payment_intents';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'amount',
        'currency',
        'customer_id',
        'status',
        'description',
        'payment_method_types',
        'capture_method',
        'confirmation_method',
        'payment_method_id',
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
        'payment_method_types' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(StripeCharge::class, 'payment_intent_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(StripeRefund::class, 'payment_intent_id', 'stripe_id')
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
     * 成功した支払いのみ
     */
    public function scopeSucceeded($query)
    {
        return $query->where('status', 'succeeded');
    }
}
