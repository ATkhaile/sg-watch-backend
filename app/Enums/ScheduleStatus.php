<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ScheduleStatus extends Enum
{
    public const ALREADY_BOOKED = 0;
    public const RESERVATIONS_AVAILABLE = 1;
    public const DAY_TRIPS_NOT_AVAILABLE = 2;
    public const OVERNIGHT_STAYS_NOT_AVAILABLE = 3;
    public const RESERVATIONS_NOT_AVAILABLE = 99;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ALREADY_BOOKED:
                return '予約済み';
                break;
            case self::RESERVATIONS_AVAILABLE:
                return '予約可能';
                break;
            case self::DAY_TRIPS_NOT_AVAILABLE:
                return '日帰り不可';
                break;
            case self::OVERNIGHT_STAYS_NOT_AVAILABLE:
                return '宿泊不可';
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
}

