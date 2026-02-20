<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class StripeCountrySpec extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    protected $table = 'stripe_country_specs';

    protected $fillable = [
        'stripe_account_id',
        'country',
        'default_currency',
        'supported_payment_currencies',
        'supported_transfer_countries',
        'verification_fields',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'supported_payment_currencies' => 'array',
        'supported_transfer_countries' => 'array',
        'verification_fields' => 'array',
    ];

    public function stripeAccount(): BelongsTo
    {
        return $this->belongsTo(StripeAccount::class, 'stripe_account_id');
    }
}
