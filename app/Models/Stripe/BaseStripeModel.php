<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

/**
 * Stripeオブジェクト用のベースモデル
 * 共通のフィールドとメソッドを提供
 */
abstract class BaseStripeModel extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    /**
     * 共通のキャスト
     */
    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];

    /**
     * Stripeアカウントとのリレーション
     */
    public function stripeAccount(): BelongsTo
    {
        return $this->belongsTo(StripeAccount::class, 'stripe_account_id');
    }

    /**
     * Stripe IDでレコードを検索
     */
    public static function findByStripeId(int $accountId, string $stripeId): ?static
    {
        return static::where('stripe_account_id', $accountId)
            ->where('stripe_id', $stripeId)
            ->first();
    }

    /**
     * Stripe IDでレコードを作成または更新
     */
    public static function upsertByStripeId(int $accountId, string $stripeId, array $data): static
    {
        $record = static::findByStripeId($accountId, $stripeId);

        if ($record) {
            $record->update($data);
            return $record;
        }

        return static::create(array_merge($data, [
            'stripe_account_id' => $accountId,
            'stripe_id' => $stripeId,
        ]));
    }

    /**
     * アカウントIDでフィルタするスコープ
     */
    public function scopeForAccount($query, int $accountId)
    {
        return $query->where('stripe_account_id', $accountId);
    }

    /**
     * 本番モードのみ取得するスコープ
     */
    public function scopeLiveOnly($query)
    {
        return $query->where('livemode', true);
    }

    /**
     * テストモードのみ取得するスコープ
     */
    public function scopeTestOnly($query)
    {
        return $query->where('livemode', false);
    }
}
