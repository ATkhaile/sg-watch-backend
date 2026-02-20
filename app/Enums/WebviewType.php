<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class WebviewType extends Enum
{
    public const HOME = 'home';
    public const CONTENT = 'content';
    public const USER = 'user';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::HOME:
                return 'Home';
            case self::CONTENT:
                return 'Content';
            case self::USER:
                return 'User';
            default:
                return '';
        }
    }

    public static function getDescriptionJp($value): string
    {
        switch ($value) {
            case self::HOME:
                return 'ホーム';
            case self::CONTENT:
                return 'コンテンツ';
            case self::USER:
                return 'ユーザー';
            default:
                return '';
        }
    }
}
