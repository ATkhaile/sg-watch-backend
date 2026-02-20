<?php

namespace App\Models\Stripe;

class StripePerson extends BaseStripeModel
{
    protected $table = 'stripe_persons';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'connected_account_id',
        'first_name',
        'last_name',
        'relationship_title',
        'email',
        'phone',
        'id_number_provided',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'id_number_provided' => 'boolean',
    ];
}
