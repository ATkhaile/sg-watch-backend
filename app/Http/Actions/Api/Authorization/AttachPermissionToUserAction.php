<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\AttachPermissionToUserUseCase;
use App\Domain\Authorization\Factory\AttachPermissionToUserRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\AttachPermissionToUserRequest;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;

class AttachPermissionToUserAction extends BaseController
{
    public function __construct(
        private AttachPermissionToUserUseCase $useCase,
        private AttachPermissionToUserRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(AttachPermissionToUserRequest $request, string $userId): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request, $userId);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
