<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripePaymentMethod extends BaseStripeModel
{
    protected $table = 'stripe_payment_methods';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'type',
        'customer_id',
        'card_brand',
        'card_last4',
        'exp_month',
        'exp_year',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'exp_month' => 'integer',
        'exp_year' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    /**
     * タイプでフィルタ
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * カードのみ
     */
    public function scopeCards($query)
    {
        return $query->where('type', 'card');
    }

    /**
     * 有効期限が切れていないカードのみ
     */
    public function scopeNotExpired($query)
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');

        return $query->where(function ($q) use ($currentYear, $currentMonth) {
            $q->where('exp_year', '>', $currentYear)
                ->orWhere(function ($q2) use ($currentYear, $currentMonth) {
                    $q2->where('exp_year', $currentYear)
                        ->where('exp_month', '>=', $currentMonth);
                });
        });
    }
}
