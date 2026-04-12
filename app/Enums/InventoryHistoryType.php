<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InventoryHistoryType extends Enum
{
    public const IMPORT = 'import';
    public const EXPORT = 'export';
}
