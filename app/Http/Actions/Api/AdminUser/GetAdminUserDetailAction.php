<?php

namespace App\Http\Actions\Api\AdminUser;

use App\Domain\AdminUser\UseCase\GetAdminUserDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\AdminUser\GetAdminUserDetailActionResource;
use App\Http\Responders\Api\AdminUser\GetAdminUserDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetAdminUserDetailAction extends BaseController
{
    private GetAdminUserDetailUseCase $useCase;
    private GetAdminUserDetailActionResponder $responder;

    public function __construct(
        GetAdminUserDetailUseCase $useCase,
        GetAdminUserDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetAdminUserDetailActionResource|JsonResponse
    {
        $user = $this->useCase->__invoke($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }

        return $this->responder->__invoke($user);
    }
}
