<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeVerificationReport extends BaseStripeModel
{
    protected $table = 'stripe_verification_reports';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'verification_session_id',
        'type',
        'document',
        'id_number',
        'selfie',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'document' => 'array',
        'id_number' => 'array',
        'selfie' => 'array',
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];

    public function verificationSession(): BelongsTo
    {
        return $this->belongsTo(StripeVerificationSession::class, 'verification_session_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
