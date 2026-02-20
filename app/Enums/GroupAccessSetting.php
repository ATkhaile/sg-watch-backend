<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GroupAccessSetting extends Enum
{
    public const CAN_VIEW_POSTS  = 'can_view_posts';
    public const CAN_POST_POSTS  = 'can_post_posts';
    public const CAN_VIEW_MEMBER = 'can_view_member';
    public const MANAGE_MEMBER   = 'manage_member';
}
