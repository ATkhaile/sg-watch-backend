<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeSource extends BaseStripeModel
{
    protected $table = 'stripe_sources';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'type',
        'amount',
        'currency',
        'owner_name',
        'owner_email',
        'status',
        'customer_id',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
