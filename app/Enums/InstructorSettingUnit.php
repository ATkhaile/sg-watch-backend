<?php

namespace App\Enums;
use BenSampo\Enum\Enum;

final class InstructorSettingUnit  extends Enum
{
    public const MINUTE = 1; //分
    public const HOUR = 2; //時間
    public const DAY = 3; //日
    public const OTHER = 4; //other
}
