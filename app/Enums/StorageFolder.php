<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StorageFolder extends Enum
{
    public const Image = 'image';

    /**
     * Folder for media uploads
     */
    public const MEDIA = 'media';

    public const CSV = 'csv';

    public const SHOP = 'shop';

    public const APP_SETTINGS = 'app-settings';

    public const GROUP = 'group';

    public const AISHIP_PRODUCT = 'aiship-product';
    
    public const  NOTIFICATION_PUSH = 'notification-push';

    public const User = 'user';

    public const POST_COMMENTS = 'post-comments';

    public const COMMUNITY_POSTS = 'community-posts';

    public const AI_KNOWLEDGE = 'ai-knowledge';
}
