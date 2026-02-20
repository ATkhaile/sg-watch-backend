<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToggleUserAdminAction extends BaseController
{
    public function __invoke(Request $request, string $userId): JsonResponse
    {
        $request->validate([
            'is_admin' => 'required|boolean',
        ]);

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('users.notfound'),
                'status_code' => 404,
            ], 404);
        }

        // Don't allow users to remove their own admin status
        $currentUser = auth()->user();
        if ($currentUser && $currentUser->id == $user->id && !$request->boolean('is_admin')) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot remove your own admin status',
                'status_code' => 403,
            ], 403);
        }

        // Update is_system_admin based on is_admin flag
        $user->is_system_admin = $request->boolean('is_admin');
        $user->save();

        $isAdmin = $user->isSystemAdmin();

        return response()->json([
            'success' => true,
            'message' => $isAdmin
                ? '管理者権限を付与しました'
                : '管理者権限を解除しました',
            'status_code' => 200,
            'data' => [
                'is_admin' => $isAdmin,
            ],
        ], 200);
    }
}
