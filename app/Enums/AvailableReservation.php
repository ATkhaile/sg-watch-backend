<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AvailableReservation extends Enum
{
    public const WEEKDAYS = 1;
    public const WEEKENDS_HOLIDAYS = 2;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::WEEKDAYS:
                return '平日';
                break;
            case self::WEEKENDS_HOLIDAYS:
                return '土日祝';
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
        return ['weekdays', 'weekends_holidays'];
    }

    public static function fromString(string $type): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$type] ?? null;
    }
}
