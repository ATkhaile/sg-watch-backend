<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\GetUsecaseGroupsUseCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class GetUsecaseGroupsAction extends BaseController
{
    public function __construct(
        private GetUsecaseGroupsUseCase $useCase
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $result = $this->useCase->__invoke();

        return response()->json($result, $result['status_code']);
    }
}
