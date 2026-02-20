<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeCreditNote extends BaseStripeModel
{
    protected $table = 'stripe_credit_notes';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'invoice_id',
        'customer_id',
        'credit_amount',
        'currency',
        'reason',
        'status',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'credit_amount' => 'integer',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(StripeInvoice::class, 'invoice_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
