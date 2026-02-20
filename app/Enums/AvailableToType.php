<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AvailableToType extends Enum
{
    public const RESERVATION_SLOTS = 1;
    public const RESERVATION_DATE = 2;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::RESERVATION_SLOTS:
                return '予約枠';
                break;
            case self::RESERVATION_DATE:
                return '予約日';
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
        return ['reservation_slots', 'reservation_date'];
    }

    public static function fromString(string $type): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$type] ?? null;
    }
}
