<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CronJobFrequency extends Enum
{
    public const MINUTE = 1;
    public const HOUR = 2;
    public const DAY = 3;
    public const MONTH = 4;
    public const YEAR = 5;
    public const CUSTOM = 6;

    protected static array $nameMap = [
        'minute' => self::MINUTE,
        'hour' => self::HOUR,
        'day' => self::DAY,
        'month' => self::MONTH,
        'year' => self::YEAR,
        'custom' => self::CUSTOM,
    ];

    protected static array $textMap = [
        self::MINUTE => 'minute',
        self::HOUR => 'hour',
        self::DAY => 'day',
        self::MONTH => 'month',
        self::YEAR => 'year',
        self::CUSTOM => 'custom',
    ];

    public static function fromName(string $name): ?self
    {
        return isset(self::$nameMap[$name])
            ? new self(self::$nameMap[$name])
            : null;
    }

    public static function names(): array
    {
        return array_keys(self::$nameMap);
    }

    public static function toText(int $value): string
    {
        return self::$textMap[$value] ?? 'minute';
    }
}
