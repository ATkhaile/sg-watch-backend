<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeTerminalLocation extends BaseStripeModel
{
    protected $table = 'stripe_terminal_locations';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'display_name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'postal_code',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];

    public function readers(): HasMany
    {
        return $this->hasMany(StripeTerminalReader::class, 'location_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
