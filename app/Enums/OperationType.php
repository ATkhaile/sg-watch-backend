<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OperationType extends Enum
{
    public const TAG_ADD = 'tag_add';
    public const TAG_REMOVE = 'tag_remove';
    // public const USER_ADD = 'user_add';
    // public const USER_REMOVE = 'user_remove';
}
