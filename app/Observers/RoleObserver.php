<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\DisplayKey;
use App\Models\DisplayKeyRoleVisibility;
use App\Models\DisplayKeyVersion;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     * 新しいロールが作成されたら、全てのDisplayKeyに対してデフォルトの表示設定を作成
     */
    public function created(Role $role): void
    {
        $currentVersion = DisplayKeyVersion::current() ?? DisplayKeyVersion::latest();
        if (!$currentVersion) {
            return;
        }

        $displayKeys = DisplayKey::where('version', $currentVersion->version)->get();

        $visibilitySettings = [];
        foreach ($displayKeys as $key) {
            $visibilitySettings[] = [
                'display_key_id' => $key->id,
                'role_id' => $role->id,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // バルクインサート
        if (!empty($visibilitySettings)) {
            DisplayKeyRoleVisibility::insert($visibilitySettings);
        }
    }

    /**
     * Handle the Role "deleted" event.
     * ロールが削除されたら、関連する表示設定も削除
     */
    public function deleted(Role $role): void
    {
        DisplayKeyRoleVisibility::where('role_id', $role->id)->delete();
    }
}
