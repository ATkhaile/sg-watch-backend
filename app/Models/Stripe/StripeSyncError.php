<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class StripeSyncError extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    protected $table = 'stripe_sync_errors';

    protected $fillable = [
        'stripe_account_id',
        'job_id',
        'object_name',
        'stripe_object_id',
        'error_type',
        'error_message',
        'error_context',
        'occurred_at',
        'resolved_flag',
        'resolved_at',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'error_context' => 'array',
        'occurred_at' => 'datetime',
        'resolved_flag' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function stripeAccount(): BelongsTo
    {
        return $this->belongsTo(StripeAccount::class, 'stripe_account_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(StripeSyncJob::class, 'job_id');
    }

    /**
     * エラーを解決済みにマーク
     */
    public function markAsResolved(): void
    {
        $this->update([
            'resolved_flag' => true,
            'resolved_at' => now(),
        ]);
    }

    /**
     * 未解決のエラーを取得
     */
    public function scopeUnresolved($query)
    {
        return $query->where('resolved_flag', false);
    }

    /**
     * 特定のオブジェクト名でフィルタ
     */
    public function scopeForObject($query, string $objectName)
    {
        return $query->where('object_name', $objectName);
    }

    /**
     * エラータイプでフィルタ
     */
    public function scopeOfType($query, string $errorType)
    {
        return $query->where('error_type', $errorType);
    }
}
