<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeSubscription extends BaseStripeModel
{
    protected $table = 'stripe_subscriptions';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'customer_id',
        'status',
        'current_period_start',
        'current_period_end',
        'cancel_at_period_end',
        'canceled_at',
        'trial_start',
        'trial_end',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'cancel_at_period_end' => 'boolean',
        'canceled_at' => 'datetime',
        'trial_start' => 'datetime',
        'trial_end' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StripeSubscriptionItem::class, 'subscription_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(StripeInvoice::class, 'subscription_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    /**
     * アクティブなサブスクリプションのみ
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * トライアル中のみ
     */
    public function scopeTrialing($query)
    {
        return $query->where('status', 'trialing');
    }

    /**
     * キャンセル済みのみ
     */
    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    /**
     * ステータスでフィルタ
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
