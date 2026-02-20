<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\AttachPermissionToRoleUseCase;
use App\Domain\Authorization\Factory\AttachPermissionToRoleRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\AttachPermissionToRoleRequest;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;

class AttachPermissionToRoleAction extends BaseController
{
    public function __construct(
        private AttachPermissionToRoleUseCase $useCase,
        private AttachPermissionToRoleRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(AttachPermissionToRoleRequest $request, string $roleId): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request, $roleId);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
