<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SignatureInfoType extends Enum
{
    public const WEB = 'web';
    public const APP = 'app';
}
