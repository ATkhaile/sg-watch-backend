<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TargetCondition extends Enum
{
    public const IN = 'in';
    public const NOT_IN = 'not_in';
    public const SAME = '==';
    public const NOT_SAME = '!=';
}
