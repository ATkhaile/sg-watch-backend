<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class JobStatus extends Enum
{
    public const PENDING = "pending";
    public const CONFIRM_INPROGRESS = "confirm_inprogress";
    public const IN_PROGRESS = "in_progress";
    public const SUCCESSFULLY = "successfully";
    public const ERROR = "error";
    public const STOPPED = "stopped";
}
