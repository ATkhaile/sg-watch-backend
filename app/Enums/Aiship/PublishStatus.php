<?php

namespace App\Enums\Aiship;

use BenSampo\Enum\Enum;

final class PublishStatus extends Enum
{
    public const PUBLIC = 'public';
    public const PRIVATE = 'private';
    public const RESERVED = 'reserved';

}
