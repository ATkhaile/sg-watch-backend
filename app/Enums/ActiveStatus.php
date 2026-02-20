<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ActiveStatus extends Enum
{
    public const ACTIVE = 'active';
    public const DEACTIVE = 'deactive';
}
