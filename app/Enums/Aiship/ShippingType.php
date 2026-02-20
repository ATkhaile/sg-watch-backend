<?php

namespace App\Enums\Aiship;

use BenSampo\Enum\Enum;

final class ShippingType extends Enum
{
    public const NORMAL = 'normal';
    public const FREE = 'free';
    public const INDIVIDUAL = 'individual';
}
