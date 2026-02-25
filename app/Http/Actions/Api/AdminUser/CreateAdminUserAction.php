<?php

namespace App\Http\Actions\Api\AdminUser;

use App\Domain\AdminUser\UseCase\CreateAdminUserUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\AdminUser\CreateAdminUserRequest;
use App\Http\Resources\Api\AdminUser\CreateAdminUserActionResource;
use App\Http\Responders\Api\AdminUser\CreateAdminUserActionResponder;

class CreateAdminUserAction extends BaseController
{
    private CreateAdminUserUseCase $useCase;
    private CreateAdminUserActionResponder $responder;

    public function __construct(
        CreateAdminUserUseCase $useCase,
        CreateAdminUserActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateAdminUserRequest $request): CreateAdminUserActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
