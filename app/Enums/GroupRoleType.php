<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GroupRoleType extends Enum
{
    public const OWNER  = 'owner';
    public const ADMIN  = 'admin';
    public const MEMBER = 'member';
    public const USER_NOT_IN_GROUP = 'user_not_in_group';
}
