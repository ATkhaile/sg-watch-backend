<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CronJobStatus extends Enum
{
    public const ACTIVE = 1;
    public const INACTIVE = 2;

    protected static array $nameMap = [
        'active' => self::ACTIVE,
        'inactive' => self::INACTIVE,
    ];

    protected static array $textMap = [
        self::ACTIVE => 'active',
        self::INACTIVE => 'inactive',
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
        return self::$textMap[$value] ?? 'active';
    }
}
