<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PushType extends Enum
{
    public const EMAIL = 'email';
    public const LINE = 'line';
    public const FIREBASE = 'firebase';
    public const PUSHER = 'pusher';
}
