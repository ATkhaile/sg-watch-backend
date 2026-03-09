<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderType extends Enum
{
    public const ONLINE = 'online';
    public const WALK_IN = 'walk_in';
}
