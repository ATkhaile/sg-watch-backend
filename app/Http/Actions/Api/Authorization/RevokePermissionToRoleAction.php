<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\RevokePermissionToRoleUseCase;
use App\Domain\Authorization\Factory\RevokePermissionToRoleRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\RevokePermissionToRoleRequest;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;

class RevokePermissionToRoleAction extends BaseController
{
    public function __construct(
        private RevokePermissionToRoleUseCase $useCase,
        private RevokePermissionToRoleRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(RevokePermissionToRoleRequest $request, string $roleId): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request, $roleId);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
