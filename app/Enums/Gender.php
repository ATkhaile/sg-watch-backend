<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Gender extends Enum
{
    public const NO_CHOOSE = 0;
    public const MALE = 1;
    public const FEMALE = 2;
    public const UNKNOWN = 3;
}
