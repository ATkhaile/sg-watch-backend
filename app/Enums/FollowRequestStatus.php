<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FollowRequestStatus extends Enum
{
    public const WAITING  = 'waiting';
    public const APPROVED = 'approved';
    public const REJECT   = 'reject';
}
