<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DeleteUserCoverImageAction extends BaseController
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

        // Delete cover image if exists
        if ($user->cover_image && Storage::disk('public')->exists($user->cover_image)) {
            Storage::disk('public')->delete($user->cover_image);
        }

        // Clear cover image in database
        $user->cover_image = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cover image deleted successfully',
            'status_code' => 200,
        ], 200);
    }
}
