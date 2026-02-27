<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StockType extends Enum
{
    public const IN_STOCK = 'in_stock';
    public const PRE_ORDER = 'pre_order';
}
