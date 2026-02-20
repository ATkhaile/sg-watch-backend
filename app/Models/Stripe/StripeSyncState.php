<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class StripeSyncState extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    protected $table = 'stripe_sync_states';

    protected $fillable = [
        'stripe_account_id',
        'object_name',
        'last_synced_id',
        'last_synced_at',
        'cursor',
        'total_count',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
        'total_count' => 'integer',
    ];

    public function stripeAccount(): BelongsTo
    {
        return $this->belongsTo(StripeAccount::class, 'stripe_account_id');
    }

    /**
     * 同期状態を更新
     */
    public function updateSyncState(string $lastSyncedId, ?string $cursor = null, int $addCount = 0): void
    {
        $this->update([
            'last_synced_id' => $lastSyncedId,
            'last_synced_at' => now(),
            'cursor' => $cursor,
            'total_count' => $this->total_count + $addCount,
        ]);
    }

    /**
     * カーソルをリセット
     */
    public function resetCursor(): void
    {
        $this->update(['cursor' => null]);
    }

    /**
     * オブジェクト名でフィルタ
     */
    public function scopeForObject($query, string $objectName)
    {
        return $query->where('object_name', $objectName);
    }
}
