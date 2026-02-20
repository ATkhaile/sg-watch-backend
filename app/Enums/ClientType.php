<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ClientType extends Enum
{
    public const WEB = 'web';
    public const NATIVE = 'native';
    public const LINE_MINI = 'line_mini';
}
