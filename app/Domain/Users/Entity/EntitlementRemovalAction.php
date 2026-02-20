<?php

namespace App\Domain\Users\Entity;

/**
 * メンバーシップ削除時のエンタイトルメント処理オプション
 */
final class EntitlementRemovalAction
{
    /** エンタイトルメントを有効のまま残す */
    public const KEEP_ENABLED = 'keep_enabled';

    /** エンタイトルメントを無効化して残す */
    public const DISABLE = 'disable';

    /** エンタイトルメントを削除する（従来の動作） */
    public const REVOKE = 'revoke';
}
