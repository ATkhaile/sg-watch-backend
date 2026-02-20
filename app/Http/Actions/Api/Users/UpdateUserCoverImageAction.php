<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateUserCoverImageAction extends BaseController
{
    public function __invoke(Request $request, string $userId): JsonResponse
    {
        $request->validate([
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:51200', // 50MB
        ]);

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('users.notfound'),
                'status_code' => 404,
            ], 404);
        }

        $coverImage = $request->file('cover_image');

        // Delete old cover image if exists
        if ($user->cover_image && Storage::disk('public')->exists($user->cover_image)) {
            Storage::disk('public')->delete($user->cover_image);
        }

        // Store new cover image in user-specific directory
        $path = $coverImage->store('covers/' . $user->id, 'public');

        // Update cover image in database
        $user->cover_image = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cover image updated successfully',
            'status_code' => 200,
            'data' => [
                'cover_image' => Storage::disk('public')->url($path),
            ],
        ], 200);
    }
}
