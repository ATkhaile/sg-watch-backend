<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeTerminalConnectionToken extends BaseStripeModel
{
    protected $table = 'stripe_terminal_connection_tokens';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'location_id',
        'secret',
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

    public function location(): BelongsTo
    {
        return $this->belongsTo(StripeTerminalLocation::class, 'location_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
