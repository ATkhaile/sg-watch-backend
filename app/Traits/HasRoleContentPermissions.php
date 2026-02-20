<?php

namespace App\Traits;

use App\Models\RoleContentPermission;
use App\Models\User;

/**
 * ロールベースのコンテンツCRUD権限チェック機能を提供するトレイト
 *
 * Columns, Series等のコンテンツモデルで使用
 */
trait HasRoleContentPermissions
{
    /**
     * ユーザーが指定した操作を実行できるかチェック
     *
     * @param User $user
     * @param string $action 'create', 'read', 'update', 'delete'
     * @return bool
     */
    public function userCan(User $user, string $action): bool
    {
        // ユーザーの全ロールを取得
        $userRoles = $user->roles->pluck('id')->toArray();

        if (empty($userRoles)) {
            return false;
        }

        // このコンテンツに対する権限設定を取得
        $permissions = RoleContentPermission::where('permissionable_type', static::class)
            ->where('permissionable_id', $this->id)
            ->whereIn('role_id', $userRoles)
            ->get();

        // いずれかのロールで権限があればOK
        foreach ($permissions as $permission) {
            if ($permission->can($action)) {
                return true;
            }
        }

        return false;
    }

    /**
     * ユーザーが読み取り権限を持つかチェック
     */
    public function userCanRead(User $user): bool
    {
        return $this->userCan($user, 'read');
    }

    /**
     * ユーザーが作成権限を持つかチェック
     */
    public function userCanCreate(User $user): bool
    {
        return $this->userCan($user, 'create');
    }

    /**
     * ユーザーが更新権限を持つかチェック
     */
    public function userCanUpdate(User $user): bool
    {
        return $this->userCan($user, 'update');
    }

    /**
     * ユーザーが削除権限を持つかチェック
     */
    public function userCanDelete(User $user): bool
    {
        return $this->userCan($user, 'delete');
    }
}
