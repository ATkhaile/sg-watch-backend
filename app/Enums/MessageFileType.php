<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MessageFileType extends Enum
{
    public const TEXT = 'text';
    public const IMAGE = 'image';
    // public const VIDEO = 'video';
    // public const FILE = 'file';
}
