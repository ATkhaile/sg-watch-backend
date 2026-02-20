<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class DeleteUserAvatarAction extends BaseController
{
    public function __invoke(string $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('users.notfound'),
                'status_code' => 404,
            ], 404);
        }

        // Delete avatar if exists
        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        // Clear avatar in database
        $user->avatar_url = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => __('users.avatar.deleted'),
            'status_code' => 200,
        ], 200);
    }
}
