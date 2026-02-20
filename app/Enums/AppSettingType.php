<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AppSettingType extends Enum
{
    public const SIDEBAR = 'sidebar';
    public const SUPPORT = 'support';
    public const FOOTER = 'footer';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::SIDEBAR:
                return 'Sidebar';
            case self::SUPPORT:
                return 'Support';
            case self::FOOTER:
                return 'Footer';
            default:
                return '';
        }
    }

    public static function getDescriptionJp($value): string
    {
        switch ($value) {
            case self::SIDEBAR:
                return 'サイドバー';
            case self::SUPPORT:
                return 'サポート';
            case self::FOOTER:
                return 'フッター';
            default:
                return '';
        }
    }

    public static function parseArray(): array
    {
        $arr = [];
        foreach (self::getValues() as $value) {
            $arr[] = [
                'id' => $value,
                'label' => self::getDescription($value),
            ];
        }
        return $arr;
    }

    public static function getStringValues(): array
    {
        return [self::SIDEBAR, self::SUPPORT, self::FOOTER];
    }

    public static function fromString(string $type): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$type] ?? null;
    }
}
