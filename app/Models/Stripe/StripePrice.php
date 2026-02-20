<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripePrice extends BaseStripeModel
{
    protected $table = 'stripe_prices';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'product_id',
        'unit_amount',
        'currency',
        'recurring_interval',
        'recurring_interval_count',
        'type',
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
        'unit_amount' => 'integer',
        'recurring_interval_count' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(StripeProduct::class, 'product_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    /**
     * 定期課金のみ取得
     */
    public function scopeRecurring($query)
    {
        return $query->where('type', 'recurring');
    }

    /**
     * 一回限りの支払いのみ取得
     */
    public function scopeOneTime($query)
    {
        return $query->where('type', 'one_time');
    }

    /**
     * アクティブな価格のみ取得
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
