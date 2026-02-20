<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * エンタイトルメントのアクションタイプ（ログ用）
 *
 * @method static static GRANT()
 * @method static static CONSUME()
 * @method static static RESET()
 * @method static static OVERRIDE()
 * @method static static EXPIRE()
 * @method static static REVOKE()
 * @method static static ENABLE()
 * @method static static DISABLE()
 */
final class EntitlementAction extends Enum
{
    /** 付与 */
    public const GRANT = 'grant';

    /** 消費 */
    public const CONSUME = 'consume';

    /** リセット（Quota用） */
    public const RESET = 'reset';

    /** オーバーライド（管理者による値変更） */
    public const OVERRIDE = 'override';

    /** 期限切れ */
    public const EXPIRE = 'expire';

    /** 剥奪 */
    public const REVOKE = 'revoke';

    /** 有効化 */
    public const ENABLE = 'enable';

    /** 無効化 */
    public const DISABLE = 'disable';

    /**
     * 日本語表示名を取得
     */
    public static function getDisplayName(string $value): string
    {
        return match ($value) {
            self::GRANT => '付与',
            self::CONSUME => '消費',
            self::RESET => 'リセット',
            self::OVERRIDE => 'オーバーライド',
            self::EXPIRE => '期限切れ',
            self::REVOKE => '剥奪',
            self::ENABLE => '有効化',
            self::DISABLE => '無効化',
            default => $value,
        };
    }
}
