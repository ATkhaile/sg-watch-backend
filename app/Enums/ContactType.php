<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ContactType extends Enum
{
    public const MEMBER_INFORMATION = 1;
    public const RESERVATIONS = 2;
    public const PAYMENT = 3;
    public const OTHER = 4;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::MEMBER_INFORMATION:
                return '会員情報について';
                break;
            case self::RESERVATIONS:
                return '予約について';
                break;
            case self::PAYMENT:
                return '支払いについて';
                break;
            case self::OTHER:
                return 'その他お問い合わせ';
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
