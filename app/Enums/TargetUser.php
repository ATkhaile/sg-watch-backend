<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TargetUser extends Enum
{
    public const VISITOR = 1;
    public const SUBSCRIPTION = 2;
    public const EXPERIENCE = 3;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::VISITOR:
                return 'ビジター';
            case self::SUBSCRIPTION:
                return 'サブスク';
            case self::EXPERIENCE:
                return '体験';
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
        return ['visitor', 'subscription', 'experience'];
    }

    public static function fromString(string $status): ?string
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return (string) $map[$status] ?? null;
    }
}
