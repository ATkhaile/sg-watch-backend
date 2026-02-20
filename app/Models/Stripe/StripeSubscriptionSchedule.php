<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeSubscriptionSchedule extends BaseStripeModel
{
    protected $table = 'stripe_subscription_schedules';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'customer_id',
        'subscription_id',
        'status',
        'phases',
        'end_behavior',
        'released_at',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'released_at' => 'datetime',
        'livemode' => 'boolean',
        'phases' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(StripeSubscription::class, 'subscription_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
