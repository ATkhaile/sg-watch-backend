<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OptionUserType extends Enum
{
    public const VISITOR = 1;
    public const SUBSCRIPTION = 2;
    public const EXPERIENCE = 3;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::VISITOR:
                return 'ビジター';
                break;
            case self::SUBSCRIPTION:
                return 'サブスク';
                break;
            case self::EXPERIENCE:
                return '体験';
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
