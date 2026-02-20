<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SearchType extends Enum
{
    public const MY_POST = 'my_post';
    public const COMMENT = 'comment';
    public const MEDIA = 'media';
    public const MENTION = 'mention';
    public const LIKE = 'like';
    public const PIN = 'pin';
    public const BOOKMARK = 'bookmark';
}
