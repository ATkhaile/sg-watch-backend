<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Users\UpdateUserAvatarRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UpdateUserAvatarAction extends BaseController
{
    public function __invoke(UpdateUserAvatarRequest $request, string $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('users.notfound'),
                'status_code' => 404,
            ], 404);
        }

        $avatar = $request->file('avatar');

        // Delete old avatar if exists
        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        // Store new avatar in user-specific directory
        $path = $avatar->store('avatars/' . $user->id, 'public');

        // Update avatar in database
        $user->avatar_url = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => __('users.avatar.updated'),
            'status_code' => 200,
            'data' => [
                'avatar_url' => Storage::disk('public')->url($path),
            ],
        ], 200);
    }
}
