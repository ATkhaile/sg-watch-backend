<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\WithdrawUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\WithdrawRequest;
use Illuminate\Http\JsonResponse;

class WithdrawAction extends BaseController
{
    public function __construct(
        private WithdrawUseCase $useCase
    ) {
    }

    public function __invoke(WithdrawRequest $request): JsonResponse
    {
        $statusEntity = $this->useCase->__invoke();

        $statusCode = $statusEntity->getStatus();

        return response()->json([
            'success' => $statusCode >= 200 && $statusCode < 300,
            'status_code' => $statusCode,
            'message' => $statusEntity->getMessage(),
        ], $statusCode);
    }
}
