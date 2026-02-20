<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeProduct extends BaseStripeModel
{
    protected $table = 'stripe_products';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'name',
        'description',
        'active',
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
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(StripePrice::class, 'product_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    /**
     * アクティブな商品のみ取得
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * 非アクティブな商品のみ取得
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }
}
