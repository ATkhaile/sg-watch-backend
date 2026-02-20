<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SsoProviderType extends Enum
{
    public const GOOGLE = 'google';
    public const LINE = 'line';
    public const APPLE = 'apple';
    public const FACEBOOK = 'facebook';
    public const MICROSOFT = 'microsoft';
    public const EMAIL = 'email';
}
