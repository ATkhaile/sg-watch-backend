<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeIssuingCardholder extends BaseStripeModel
{
    protected $table = 'stripe_issuing_cardholders';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'name',
        'email',
        'phone',
        'status',
        'type',
        'billing',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'billing' => 'array',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(StripeIssuingCard::class, 'cardholder_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
