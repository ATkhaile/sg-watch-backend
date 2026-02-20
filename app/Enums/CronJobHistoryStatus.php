<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CronJobHistoryStatus extends Enum
{
    public const SUCCESS = 1;
    public const FAILED = 2;
    public const TIMEOUT = 3;

    protected static array $nameMap = [
        'success' => self::SUCCESS,
        'failed' => self::FAILED,
        'timeout' => self::TIMEOUT,
    ];

    protected static array $textMap = [
        self::SUCCESS => 'success',
        self::FAILED => 'failed',
        self::TIMEOUT => 'timeout',
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
        return self::$textMap[$value] ?? 'failed';
    }
}
