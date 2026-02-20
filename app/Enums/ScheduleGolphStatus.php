<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ScheduleGolphStatus extends Enum
{
    public const ALREADY_BOOKED = 1;
    public const RESERVATIONS_AVAILABLE = 2;
    public const RESERVATIONS_NOT_AVAILABLE = 3;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ALREADY_BOOKED:
                return '予約済み';
                break;
            case self::RESERVATIONS_AVAILABLE:
                return '通常予約可能';
                break;
            case self::RESERVATIONS_NOT_AVAILABLE:
                return '予約不可';
                break;
            default:
                return '';
                break;
        }
    }
    public static function parseArray()
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
        return [
            self::ALREADY_BOOKED => 'already_booked',
            self::RESERVATIONS_AVAILABLE => 'reservations_available',
            self::RESERVATIONS_NOT_AVAILABLE => 'reservations_not_available',
        ];
    }
}
