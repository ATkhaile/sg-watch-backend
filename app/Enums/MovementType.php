<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MovementType extends Enum
{
    public const QUARTZ = 'quartz';       // Pin
    public const AUTOMATIC = 'automatic'; // Cơ tự động
    public const MANUAL = 'manual';       // Cơ lên dây
    public const SOLAR = 'solar';         // Năng lượng mặt trời
    public const KINETIC = 'kinetic';     // Kinetic
}
