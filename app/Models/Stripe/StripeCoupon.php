<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeCoupon extends BaseStripeModel
{
    protected $table = 'stripe_coupons';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'name',
        'amount_off',
        'percent_off',
        'duration',
        'duration_in_months',
        'currency',
        'redeem_by',
        'max_redemptions',
        'times_redeemed',
        'valid',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'redeem_by' => 'datetime',
        'livemode' => 'boolean',
        'valid' => 'boolean',
        'amount_off' => 'integer',
        'percent_off' => 'decimal:2',
        'max_redemptions' => 'integer',
        'times_redeemed' => 'integer',
        'duration_in_months' => 'integer',
    ];

    public function promotionCodes(): HasMany
    {
        return $this->hasMany(StripePromotionCode::class, 'coupon_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
