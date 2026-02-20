<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AvailableUnit extends Enum
{
    public const MINUS = 1;
    public const HOUR = 2;
    public const DAY = 3;
    public const MONTH = 4;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::MINUS:
                return '分';
                break;
            case self::HOUR:
                return '時間';
                break;
            case self::DAY:
                return '日';
                break;
            case self::MONTH:
                return '月';
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
        return ['minus', 'hour', 'day', 'month'];
    }

    public static function fromString(string $unit): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$unit] ?? null;
    }
}
