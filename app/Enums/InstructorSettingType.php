<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InstructorSettingType extends Enum
{
    public const RESERVATION_SLOT = 1; //予約枠
    public const RESERVATION_DATE = 2; //予約日
}
