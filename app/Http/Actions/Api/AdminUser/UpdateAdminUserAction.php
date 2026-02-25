<?php

namespace App\Http\Actions\Api\AdminUser;

use App\Domain\AdminUser\UseCase\UpdateAdminUserUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\AdminUser\UpdateAdminUserRequest;
use App\Http\Resources\Api\AdminUser\UpdateAdminUserActionResource;
use App\Http\Responders\Api\AdminUser\UpdateAdminUserActionResponder;

class UpdateAdminUserAction extends BaseController
{
    private UpdateAdminUserUseCase $useCase;
    private UpdateAdminUserActionResponder $responder;

    public function __construct(
        UpdateAdminUserUseCase $useCase,
        UpdateAdminUserActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateAdminUserRequest $request, int $id): UpdateAdminUserActionResource
    {
        $result = $this->useCase->__invoke($id, $request->validated());

        return $this->responder->__invoke($result);
    }
}
