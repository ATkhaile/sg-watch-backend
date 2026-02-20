<?php

namespace App\Models\Stripe;

class StripePaymentLink extends BaseStripeModel
{
    protected $table = 'stripe_payment_links';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'url',
        'active',
        'currency',
        'amount_total',
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
        'active' => 'boolean',
        'amount_total' => 'integer',
    ];

    /**
     * アクティブなリンクのみ
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * 非アクティブなリンクのみ
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }
}
