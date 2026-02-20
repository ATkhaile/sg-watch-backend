<?php

namespace App\Models\Stripe;

class StripeTaxCode extends BaseStripeModel
{
    protected $table = 'stripe_tax_codes';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'name',
        'description',
        'remarks',
        'creator',
        'updater',
    ];

    // TaxCodeにはlivemodeやstripe_createdがないためキャストをオーバーライド
    protected $casts = [];
}
