<?php

namespace App\Models\Stripe;

class StripeReportingReportRun extends BaseStripeModel
{
    protected $table = 'stripe_reporting_report_runs';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'report_type',
        'status',
        'result_id',
        'parameters',
        'succeeded_at',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'parameters' => 'array',
        'succeeded_at' => 'datetime',
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];
}
