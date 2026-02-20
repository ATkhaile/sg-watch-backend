<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class StripeSyncJob extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    protected $table = 'stripe_sync_jobs';

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    public const JOB_TYPE_BACKFILL = 'backfill';
    public const JOB_TYPE_INCREMENTAL = 'incremental';
    public const JOB_TYPE_WEBHOOK = 'webhook';
    public const JOB_TYPE_MANUAL = 'manual';

    protected $fillable = [
        'stripe_account_id',
        'object_name',
        'status',
        'job_type',
        'scheduled_at',
        'started_at',
        'finished_at',
        'processed_count',
        'error_count',
        'message',
        'cancelled_by',
        'cancelled_at',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'processed_count' => 'integer',
        'error_count' => 'integer',
    ];

    public function stripeAccount(): BelongsTo
    {
        return $this->belongsTo(StripeAccount::class, 'stripe_account_id');
    }

    public function errors(): HasMany
    {
        return $this->hasMany(StripeSyncError::class, 'job_id');
    }

    /**
     * ジョブを開始
     */
    public function start(): void
    {
        $this->update([
            'status' => self::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);
    }

    /**
     * ジョブを完了
     */
    public function complete(?string $message = null): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'finished_at' => now(),
            'message' => $message,
        ]);
    }

    /**
     * ジョブを失敗
     */
    public function fail(string $message): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'finished_at' => now(),
            'message' => $message,
        ]);
    }

    /**
     * ジョブをキャンセル（ユーザー操作）
     */
    public function cancel(string $cancelledBy, ?string $message = null): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'finished_at' => now(),
            'cancelled_by' => $cancelledBy,
            'cancelled_at' => now(),
            'message' => $message ?? 'ユーザーによりキャンセルされました',
        ]);
    }

    /**
     * 予定日時を変更
     */
    public function reschedule(\DateTimeInterface $scheduledAt, string $updater): void
    {
        $this->update([
            'scheduled_at' => $scheduledAt,
            'updater' => $updater,
        ]);
    }

    /**
     * 即時実行（scheduled_atをnullに設定）
     */
    public function executeImmediately(string $updater): void
    {
        $this->update([
            'scheduled_at' => null,
            'updater' => $updater,
        ]);
    }

    /**
     * 実行可能かどうか
     */
    public function isExecutable(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        // scheduled_atがnullまたは過去なら実行可能
        if ($this->scheduled_at === null) {
            return true;
        }

        return $this->scheduled_at->lte(now());
    }

    /**
     * 処理件数を加算
     */
    public function incrementProcessedCount(int $count = 1): void
    {
        $this->increment('processed_count', $count);
    }

    /**
     * エラー件数を加算
     */
    public function incrementErrorCount(int $count = 1): void
    {
        $this->increment('error_count', $count);
    }

    /**
     * 進行中のジョブを取得
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * 特定のオブジェクト名でフィルタ
     */
    public function scopeForObject($query, string $objectName)
    {
        return $query->where('object_name', $objectName);
    }
}
