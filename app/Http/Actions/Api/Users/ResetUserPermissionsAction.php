<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * ユーザーの権限をロールに紐づいた権限のみにリセットする
 * ロール外の直接付与された権限を全て削除する
 */
class ResetUserPermissionsAction extends BaseController
{
    public function __invoke(int $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'status_code' => 404,
            ], 404);
        }

        DB::beginTransaction();
        try {
            // ユーザーのロールに紐づく権限IDを取得
            $rolePermissionIds = $user->roles()
                ->with('permissions')
                ->get()
                ->pluck('permissions')
                ->flatten()
                ->pluck('id')
                ->unique()
                ->toArray();

            // ユーザーの現在の直接付与された権限を取得
            $currentUserPermissionIds = $user->permissions()->pluck('permissions.id')->toArray();

            // ロール外の権限（削除対象）を特定
            $permissionsToRemove = array_diff($currentUserPermissionIds, $rolePermissionIds);

            // ロール外の権限を削除（user_permissionsピボットテーブルから）
            if (!empty($permissionsToRemove)) {
                $user->permissions()->detach($permissionsToRemove);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User permissions have been reset to role-based permissions only',
                'status_code' => 200,
                'data' => [
                    'removed_permission_count' => count($permissionsToRemove),
                    'remaining_direct_permissions' => count(array_intersect($currentUserPermissionIds, $rolePermissionIds)),
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset permissions: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }
}
