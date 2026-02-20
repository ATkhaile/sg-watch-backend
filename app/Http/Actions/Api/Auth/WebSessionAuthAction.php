<?php

namespace App\Http\Actions\Api\Auth;

use App\Http\Controllers\BaseController;
use App\Enums\StatusCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebSessionAuthAction extends BaseController
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'status_code' => StatusCode::OK,
            'message' => 'Authenticated',
        ], StatusCode::OK);
    }
}