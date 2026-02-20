<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PostReactionTargetType extends Enum
{
    public const POST = 'post';
    public const COMMENT = 'comment';
}
