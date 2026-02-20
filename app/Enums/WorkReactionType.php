<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class WorkReactionType extends Enum
{
    public const YES = 'yes';
    public const NO = 'no';
    public const LIKE = 'like';

    public static function getDescriptionJp($value): string
    {
        switch ($value) {
            case self::YES:
                return 'はい';
            case self::NO:
                return 'いいえ';
            case self::LIKE:
                return 'いいね';
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
                'label' => self::getDescription($value),
            ];
        }
        return $arr;
    }

    public static function getStringValues(): array
    {
        return ['yes', 'no', 'like'];
    }

    public static function fromString(string $status): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$status] ?? null;
    }
}
