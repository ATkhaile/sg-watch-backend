<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LimitType extends Enum
{
    public const LIMIT = 1;
    public const NO_LIMIT = 2;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::LIMIT:
                return '使用回数制限';
            case self::NO_LIMIT:
                return '無制限';
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
        return ['limit', 'no_limit'];
    }

    public static function fromString(string $status): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$status] ?? null;
    }
}
