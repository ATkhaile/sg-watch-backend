<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class StripeWebhookEvent extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    protected $table = 'stripe_webhook_events';

    protected $fillable = [
        'stripe_account_id',
        'event_id',
        'type',
        'data_object',
        'received_at',
        'processed_flag',
        'processed_at',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'data_object' => 'array',
        'received_at' => 'datetime',
        'processed_flag' => 'boolean',
        'processed_at' => 'datetime',
    ];

    public function stripeAccount(): BelongsTo
    {
        return $this->belongsTo(StripeAccount::class, 'stripe_account_id');
    }

    /**
     * イベントを処理済みにマーク
     */
    public function markAsProcessed(): void
    {
        $this->update([
            'processed_flag' => true,
            'processed_at' => now(),
        ]);
    }

    /**
     * 未処理のイベントを取得
     */
    public function scopeUnprocessed($query)
    {
        return $query->where('processed_flag', false);
    }

    /**
     * イベントタイプでフィルタ
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
