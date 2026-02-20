<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ConditionType extends Enum
{
    public const AND = 'AND';
    public const OR = 'OR';
}
