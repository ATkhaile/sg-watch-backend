<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ProductCondition extends Enum
{
    public const NEW = 'new';
    public const DISPLAY = 'display';
    public const USED = 'used';
}
