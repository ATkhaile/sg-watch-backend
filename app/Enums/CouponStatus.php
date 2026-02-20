<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CouponStatus extends Enum
{
    public const ACTIVE = 1;
    public const STOPPED = 2;
    public const ONLY_USER = 3;
    public const UN_PUBLISH = 4;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ACTIVE:
                return '使用可';
                break;
            case self::STOPPED:
                return '使用停止';
                break;
            case self::ONLY_USER:
                return '限定公開';
                break;
            case self::UN_PUBLISH:
                return '非公開';
                break;
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
        return ['active', 'stopped', 'only_user', 'un_publish'];
    }

    public static function fromString(string $status): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$status] ?? null;
    }
}
