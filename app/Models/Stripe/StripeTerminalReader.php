<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeTerminalReader extends BaseStripeModel
{
    protected $table = 'stripe_terminal_readers';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'label',
        'location_id',
        'device_type',
        'status',
        'serial_number',
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
