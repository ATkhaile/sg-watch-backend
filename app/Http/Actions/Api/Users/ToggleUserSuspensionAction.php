<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToggleUserSuspensionAction extends BaseController
{
    public function __invoke(Request $request, string $userId): JsonResponse
    {
        $request->validate([
            'is_suspended' => 'required|boolean',
            'reason' => 'nullable|string|max:1000',
        ]);

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('users.notfound'),
                'status_code' => 404,
            ], 404);
        }

        // 自分自身を凍結することはできない
        $currentUser = auth()->user();
        if ($currentUser && $currentUser->id == $user->id) {
            return response()->json([
                'success' => false,
                'message' => '自分自身を凍結することはできません',
                'status_code' => 403,
            ], 403);
        }

        // システム管理者は凍結できない
        if ($user->is_system_admin) {
            return response()->json([
                'success' => false,
                'message' => '管理者は凍結できません',
                'status_code' => 403,
            ], 403);
        }

        $shouldSuspend = $request->boolean('is_suspended');
        $reason = $request->input('reason');
        $performedBy = $currentUser ? $currentUser->id : null;

        if ($shouldSuspend) {
            // 凍結する
            $result = $user->suspend($reason, $performedBy);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'このユーザーは既に凍結されています',
                    'status_code' => 400,
                ], 400);
            }
            $message = 'ユーザーを凍結しました';
        } else {
            // 凍結解除
            $result = $user->unsuspend($reason, $performedBy);
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'このユーザーは凍結されていません',
                    'status_code' => 400,
                ], 400);
            }
            $message = 'ユーザーの凍結を解除しました';
        }

        // 更新後のユーザー情報を取得
        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => $message,
            'status_code' => 200,
            'data' => [
                'is_suspended' => $user->is_suspended,
                'suspended_at' => $user->suspended_at?->format('Y/m/d H:i:s'),
                'suspension_count' => $user->suspension_count,
            ],
        ], 200);
    }
}
