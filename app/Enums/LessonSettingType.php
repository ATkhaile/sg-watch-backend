<?php

namespace App\Enums;
use BenSampo\Enum\Enum;

final class LessonSettingType extends Enum
{
    public const RESERVATION_SLOT = 1;  //予約枠
    public const RESERVATION_DATE = 2;  //予約日
}
