<?php

namespace App\Models\Stripe;

class StripeEvent extends BaseStripeModel
{
    protected $table = 'stripe_events';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'type',
        'api_version',
        'data',
        'request_id',
        'livemode',
        'pending_webhooks',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
        'pending_webhooks' => 'boolean',
        'data' => 'array',
    ];

    /**
     * イベントタイプでフィルタするスコープ
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * イベントタイププレフィックスでフィルタするスコープ
     */
    public function scopeOfTypePrefix($query, string $prefix)
    {
        return $query->where('type', 'like', $prefix . '%');
    }
}
