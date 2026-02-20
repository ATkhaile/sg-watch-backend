<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PlanStatus extends Enum
{
    public const ACTIVE = 1;
    public const UN_ACTIVE = 2;
    public const LIMITED = 3;
    public const UN_PUBLISH = 4;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ACTIVE:
                return '受付中';
                break;
            case self::UN_ACTIVE:
                return '受付停止';
                break;
            case self::LIMITED:
                return '非公開';
                break;
            case self::UN_PUBLISH:
                return '限定公開';
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
        return ['active', 'un_active', 'limited', 'un_publish'];
    }

    public static function fromString(string $status): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$status] ?? null;
    }
}
