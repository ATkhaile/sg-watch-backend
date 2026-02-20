<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;


final class SaunaScheduleStatus extends Enum
{
    public const RESERVATIONS_AVAILABLE = 1;
    public const AVAILABLE = 2;
    public const RESERVATION_FULL = 3;
    public const RESERVATIONS_NOT_AVAILABLE = 4;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::RESERVATIONS_AVAILABLE:
                return '予約可能';
                break;
            case self::AVAILABLE:
                return '空きあり';
                break;
            case self::RESERVATION_FULL:
                return '予約済み';
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
            self::RESERVATIONS_AVAILABLE => 'reservations_available',
            self::AVAILABLE => 'available',
            self::RESERVATION_FULL => 'reservation_full',
            self::RESERVATIONS_NOT_AVAILABLE => 'reservations_not_available',
        ];
    }
}

