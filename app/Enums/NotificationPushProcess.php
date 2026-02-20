<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class NotificationPushProcess extends Enum
{
    public const WAITING = 'waiting';
    public const IN_PROGRESS  = 'in_progress';
    public const SUCCESSFULLY = 'successfully';
}
