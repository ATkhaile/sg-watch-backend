<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Carbon\Carbon;

class StripeSyncSettings extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    protected $table = 'stripe_sync_settings';

    public const FREQUENCY_30MIN = '30min';
    public const FREQUENCY_1_HOUR = '1_hour';
    public const FREQUENCY_6_HOURS = '6_hours';
    public const FREQUENCY_12_HOURS = '12_hours';
    public const FREQUENCY_1_DAY = '1_day';
    public const FREQUENCY_2_DAYS = '2_days';
    public const FREQUENCY_3_DAYS = '3_days';
    public const FREQUENCY_1_WEEK = '1_week';

    protected $fillable = [
        'stripe_account_id',
        'auto_sync_enabled',
        'webhook_enabled',
        'sync_frequency',
        'last_auto_sync_at',
        'next_auto_sync_at',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'auto_sync_enabled' => 'boolean',
        'webhook_enabled' => 'boolean',
        'last_auto_sync_at' => 'datetime',
        'next_auto_sync_at' => 'datetime',
    ];

    /**
     * 同期頻度の選択肢を取得
     */
    public static function getFrequencyOptions(): array
    {
        return [
            self::FREQUENCY_30MIN => '30分',
            self::FREQUENCY_1_HOUR => '1時間',
            self::FREQUENCY_6_HOURS => '6時間',
            self::FREQUENCY_12_HOURS => '12時間',
            self::FREQUENCY_1_DAY => '1日',
            self::FREQUENCY_2_DAYS => '2日',
            self::FREQUENCY_3_DAYS => '3日',
            self::FREQUENCY_1_WEEK => '1週間',
        ];
    }

    /**
     * Stripeアカウントとのリレーション
     */
    public function stripeAccount(): BelongsTo
    {
        return $this->belongsTo(StripeAccount::class, 'stripe_account_id');
    }

    /**
     * グローバル設定を取得または作成
     */
    public static function getGlobalSettings(): self
    {
        return self::firstOrCreate(
            ['stripe_account_id' => null],
            [
                'auto_sync_enabled' => false,
                'webhook_enabled' => true,
                'sync_frequency' => self::FREQUENCY_6_HOURS,
                'creator' => 'system',
            ]
        );
    }

    /**
     * アカウント固有の設定を取得
     */
    public static function getAccountSettings(int $accountId): ?self
    {
        return self::where('stripe_account_id', $accountId)->first();
    }

    /**
     * 次回同期時刻を計算
     */
    public function calculateNextSyncAt(): Carbon
    {
        return match ($this->sync_frequency) {
            self::FREQUENCY_30MIN => Carbon::now()->addMinutes(30),
            self::FREQUENCY_1_HOUR => Carbon::now()->addHour(),
            self::FREQUENCY_6_HOURS => Carbon::now()->addHours(6),
            self::FREQUENCY_12_HOURS => Carbon::now()->addHours(12),
            self::FREQUENCY_1_DAY => Carbon::now()->addDay(),
            self::FREQUENCY_2_DAYS => Carbon::now()->addDays(2),
            self::FREQUENCY_3_DAYS => Carbon::now()->addDays(3),
            self::FREQUENCY_1_WEEK => Carbon::now()->addWeek(),
            default => Carbon::now()->addHours(6),
        };
    }

    /**
     * 同期を実行したことを記録
     */
    public function recordSyncExecution(?string $updater = 'system'): void
    {
        $this->update([
            'last_auto_sync_at' => now(),
            'next_auto_sync_at' => $this->calculateNextSyncAt(),
            'updater' => $updater,
        ]);
    }

    /**
     * 同期が必要かどうかを判定
     */
    public function shouldSync(): bool
    {
        if (!$this->auto_sync_enabled) {
            return false;
        }

        if (!$this->next_auto_sync_at) {
            return true;
        }

        return Carbon::now()->gte($this->next_auto_sync_at);
    }

    /**
     * グローバル設定かどうか
     */
    public function isGlobal(): bool
    {
        return is_null($this->stripe_account_id);
    }
}
