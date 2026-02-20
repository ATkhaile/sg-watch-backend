<?php

namespace App\Models\Stripe;

class StripeSigmaScheduledQueryRun extends BaseStripeModel
{
    protected $table = 'stripe_sigma_scheduled_query_runs';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'title',
        'sql',
        'status',
        'result_available_until',
        'file_id',
        'error',
        'data_load_time',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'data_load_time' => 'datetime',
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];
}
