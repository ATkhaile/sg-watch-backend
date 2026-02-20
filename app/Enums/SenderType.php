<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SenderType extends Enum
{
    public const ANNOUNCEMENT = "announcement";
    public const NEWSLETTER = "newsletter";
    public const SYSTEM_EMAIL = "system_email";

    protected static array $nameMap = [
        self::ANNOUNCEMENT => "news@gameagelayer.com",
        self::NEWSLETTER => "magagine@gameagelayer.com",
        self::SYSTEM_EMAIL => "system@gameagelayer.com",
    ];

    public static function getMailFrom(string $value): string
    {
        return self::$nameMap[$value] ?? config('mail.from.address');
    }
}
