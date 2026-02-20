<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * エンタイトルメントのタイプ
 *
 * @method static static ON_OFF()
 * @method static static QUOTA()
 * @method static static CONSUMABLE()
 */
final class EntitlementType extends Enum
{
    /**
     * ON/OFF型: 機能の有効/無効を制御
     * 例: paywall_disabled, ads_free, premium_content_access
     */
    public const ON_OFF = 'on_off';

    /**
     * QUOTA型: 使用上限（定期的にリセット可能）
     * 例: ai_chat_monthly_limit, storage_limit_gb
     */
    public const QUOTA = 'quota';

    /**
     * CONSUMABLE型: 消費型（使い切り、追加購入可能）
     * 例: ai_tokens, download_credits
     */
    public const CONSUMABLE = 'consumable';

    /**
     * 日本語表示名を取得
     */
    public static function getDisplayName(string $value): string
    {
        return match ($value) {
            self::ON_OFF => 'ON/OFF',
            self::QUOTA => '使用上限（QUOTA）',
            self::CONSUMABLE => '消費型（CONSUMABLE）',
            default => $value,
        };
    }

    /**
     * 説明を取得
     */
    public static function getDescription(string $value): string
    {
        return match ($value) {
            self::ON_OFF => '機能の有効/無効を切り替えます',
            self::QUOTA => '使用回数や容量の上限を設定します（定期リセット可能）',
            self::CONSUMABLE => '消費型のリソースを管理します（購入・付与で追加）',
            default => '',
        };
    }
}
