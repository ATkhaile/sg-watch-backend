<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DocumentType extends Enum
{
    public const TERM_OF_SERVICE = 1;
    public const SPECIFIED       = 2;
    public const PRIVACY_POLICY  = 3;

    protected static array $nameMap = [
        'term_of_service' => self::TERM_OF_SERVICE,
        'specified' => self::SPECIFIED,
        'privacy_policy' => self::PRIVACY_POLICY
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
}
