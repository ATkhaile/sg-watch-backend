<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AccountUseType extends Enum
{
    public const ONLY_ONE_ACCOUNT = 1;
    public const NO_LIMIT_ACCOUNT = 2;
    public const COUNT_USE = 3;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ONLY_ONE_ACCOUNT:
                return '1アカウントのみ';
            case self::NO_LIMIT_ACCOUNT:
                return '何アカウントでも';
            case self::COUNT_USE:
                return 'アカウントまで';
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
        return ['only_one_account', 'no_limit_account', 'count_use'];
    }

    public static function fromString(string $status): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$status] ?? null;
    }
}
