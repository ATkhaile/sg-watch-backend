<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeSetupIntent extends BaseStripeModel
{
    protected $table = 'stripe_setup_intents';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'customer_id',
        'payment_method_id',
        'payment_method_types',
        'status',
        'usage',
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
        'payment_method_types' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(StripePaymentMethod::class, 'payment_method_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
