<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeInvoice extends BaseStripeModel
{
    protected $table = 'stripe_invoices';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'customer_id',
        'subscription_id',
        'status',
        'number',
        'billing_reason',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'currency',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'due_date' => 'datetime',
        'subtotal' => 'integer',
        'tax' => 'integer',
        'total' => 'integer',
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

    public function items(): HasMany
    {
        return $this->hasMany(StripeInvoiceItem::class, 'invoice_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(StripeCharge::class, 'invoice_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    /**
     * ステータスでフィルタ
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * 支払い済みのみ
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * 未払いのみ
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
