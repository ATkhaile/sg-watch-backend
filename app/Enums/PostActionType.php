<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PostActionType extends Enum
{
    public const NOT_INTERESTED = 'not_interested';
    public const REPORT = 'report';
    public const MUTE = 'mute';
    public const PIN = 'pin';
}
