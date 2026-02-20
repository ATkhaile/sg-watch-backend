<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DiscountOptionType extends Enum
{
    public const INCLUDE_OPTIONS = 1;
    public const NO_INCLUDE_OPTIONS = 2;
    public const ONLY_OPTION = 3;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::INCLUDE_OPTIONS:
                return 'オプションを含む';
            case self::NO_INCLUDE_OPTIONS:
                return 'オプションを含まない';
            case self::ONLY_OPTION:
                return 'オプションのみ';
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
                'label' => self::getDescription($value)
            ];
        }
        return $arr;
    }

    public static function getStringValues(): array
    {
        return ['include_options', 'no_include_options', 'only_option'];
    }

    public static function fromString(string $status): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$status] ?? null;
    }
}
