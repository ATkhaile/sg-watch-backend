<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 報告ステータス
 */
final class ReportStatus extends Enum
{
    /** 未対応 */
    public const PENDING = 'pending';

    /** 確認中 */
    public const REVIEWING = 'reviewing';

    /** 対応済み（措置実施） */
    public const RESOLVED = 'resolved';

    /** 却下（問題なし） */
    public const DISMISSED = 'dismissed';
}
