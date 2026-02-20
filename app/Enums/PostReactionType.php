<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PostReactionType extends Enum
{
    public const LIKE  = 'like';
    public const LOVE  = 'love';
    public const CARE  = 'care';
    public const HAHA  = 'haha';
    public const WOW   = 'wow';
    public const SAD   = 'sad';
    public const ANGRY = 'angry';
}
