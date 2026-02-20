<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class StripeDashboardStats extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'stripe_dashboard_stats';

    protected $fillable = [
        'stripe_account_id',
        'payment_links_count',
        'prices_count',
        'products_count',
        'customers_count',
        'subscriptions_count',
        'invoices_count',
        'charges_count',
        'payment_intents_count',
        'refunds_count',
        'events_count',
        'balance_transactions_count',
        'checkout_sessions_count',
        'invoice_items_count',
        'payouts_count',
        'disputes_count',
        'credit_notes_count',
        'payment_methods_count',
        'payment_link_line_items_count',
        'subscription_items_count',
        'last_synced_at',
    ];

    protected $casts = [
        'payment_links_count' => 'integer',
        'prices_count' => 'integer',
        'products_count' => 'integer',
        'customers_count' => 'integer',
        'subscriptions_count' => 'integer',
        'invoices_count' => 'integer',
        'charges_count' => 'integer',
        'payment_intents_count' => 'integer',
        'refunds_count' => 'integer',
        'events_count' => 'integer',
        'balance_transactions_count' => 'integer',
        'checkout_sessions_count' => 'integer',
        'invoice_items_count' => 'integer',
        'payouts_count' => 'integer',
        'disputes_count' => 'integer',
        'credit_notes_count' => 'integer',
        'payment_methods_count' => 'integer',
        'payment_link_line_items_count' => 'integer',
        'subscription_items_count' => 'integer',
        'last_synced_at' => 'datetime',
    ];

    public function stripeAccount()
    {
        return $this->belongsTo(StripeAccount::class, 'stripe_account_id', 'stripe_account_id');
    }
}
