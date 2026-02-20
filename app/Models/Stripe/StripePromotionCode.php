<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripePromotionCode extends BaseStripeModel
{
    protected $table = 'stripe_promotion_codes';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'coupon_id',
        'code',
        'customer_id',
        'max_redemptions',
        'times_redeemed',
        'active',
        'expires_at',
        'restrictions',
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
        'active' => 'boolean',
        'max_redemptions' => 'integer',
        'times_redeemed' => 'integer',
        'restrictions' => 'array',
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(StripeCoupon::class, 'coupon_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
