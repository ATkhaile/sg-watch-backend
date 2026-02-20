<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\TogglePermissionActiveUseCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class TogglePermissionActiveAction extends BaseController
{
    public function __construct(
        private TogglePermissionActiveUseCase $useCase
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $result = $this->useCase->__invoke((int) $id);

        return response()->json($result, $result['status_code']);
    }
}
