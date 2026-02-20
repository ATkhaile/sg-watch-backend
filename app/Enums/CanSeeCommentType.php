<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CanSeeCommentType extends Enum
{
    public const ALL = 'all';
    public const USER_FOLLOW = 'user_follow';
    public const DISABLED = 'disabled';
}
