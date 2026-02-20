<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class NewStatus extends Enum
{
    public const PUBLISH = 1;
    public const DRAFT = 2;
}
