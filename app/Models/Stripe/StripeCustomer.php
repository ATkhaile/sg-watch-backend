<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeCustomer extends BaseStripeModel
{
    protected $table = 'stripe_customers';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'name',
        'email',
        'phone',
        'description',
        'invoice_prefix',
        'default_payment_method_id',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(StripeSubscription::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(StripeInvoice::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(StripeCharge::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(StripePaymentMethod::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    public function paymentIntents(): HasMany
    {
        return $this->hasMany(StripePaymentIntent::class, 'customer_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }

    /**
     * メールでフィルタするスコープ
     */
    public function scopeByEmail($query, string $email)
    {
        return $query->where('email', $email);
    }
}
