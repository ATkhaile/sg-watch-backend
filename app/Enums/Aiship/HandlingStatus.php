<?php

namespace App\Enums\Aiship;

use BenSampo\Enum\Enum;

final class HandlingStatus extends Enum
{
    public const ACTIVE = 'active';
    public const STOP_SALE = 'stop_sale';
    public const DISCONTINUED = 'discontinued';
}
