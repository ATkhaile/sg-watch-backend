<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ExpireType extends Enum
{
    public const EXPIRE = 1;
    public const NO_EXPIRE = 2;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::EXPIRE:
                return '期限あり';
            case self::NO_EXPIRE:
                return '期限なし';
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
        return ['expire', 'no_expire'];
    }

    public static function fromString(string $status): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$status] ?? null;
    }
}
