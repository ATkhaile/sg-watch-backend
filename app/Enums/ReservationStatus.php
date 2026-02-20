<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ReservationStatus extends Enum
{
    public const CONFIRM = 1;
    public const APPROVED = 2;
    public const CANCELED = 3;
}
