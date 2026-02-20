<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * エンタイトルメントの付与元
 *
 * @method static static MEMBERSHIP()
 * @method static static DIRECT()
 * @method static static PURCHASE()
 * @method static static PROMOTION()
 */
final class EntitlementSource extends Enum
{
    /** Membership経由で付与 */
    public const MEMBERSHIP = 'membership';

    /** 管理者による直接付与 */
    public const DIRECT = 'direct';

    /** 購入による付与 */
    public const PURCHASE = 'purchase';

    /** プロモーション・キャンペーンによる付与 */
    public const PROMOTION = 'promotion';

    /**
     * 日本語表示名を取得
     */
    public static function getDisplayName(string $value): string
    {
        return match ($value) {
            self::MEMBERSHIP => 'メンバーシップ',
            self::DIRECT => '直接付与',
            self::PURCHASE => '購入',
            self::PROMOTION => 'プロモーション',
            default => $value,
        };
    }
}
