<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TimeLineType extends Enum
{
    public const ALL_POST   = 'all_post';
    public const USER_POST  = 'user_post';
    public const GROUP_POST = 'group_post';

    public static function getStringValues(): array
    {
        return [
            self::ALL_POST,
            self::USER_POST,
            self::GROUP_POST,
        ];
    }
}
