<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ShippingMethod extends Enum
{
    public const STANDARD = 'standard';
    public const EXPRESS = 'express';
    public const PICKUP = 'pickup';
}
