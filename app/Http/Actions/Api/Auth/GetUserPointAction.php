<?php

namespace App\Http\Actions\Api\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class GetUserPointAction extends BaseController
{
    public function __invoke(): JsonResponse
    {
        $user = auth()->user();

        return response()->json([
            'message' => 'User point retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'point' => $user->point ?? 0,
            ],
        ]);
    }
}
