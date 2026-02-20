<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\UseCase\GetUserMembershipsUseCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class GetUserMembershipsAction extends BaseController
{
    public function __construct(
        private GetUserMembershipsUseCase $useCase
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        $memberships = $this->useCase->__invoke($id);
        return response()->json([
            'success' => true,
            'data' => $memberships,
        ]);
    }
}
