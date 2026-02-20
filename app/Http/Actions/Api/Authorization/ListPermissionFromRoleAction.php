<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\ListPermissionFromRoleUseCase;
use App\Domain\Authorization\Factory\ListPermissionFromRoleRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Authorization\ListPermissionFromRoleActionResource;
use App\Http\Responders\Api\Authorization\ListPermissionFromRoleActionResponder;
use App\Http\Requests\Api\Authorization\ListPermissionFromRoleRequest;

class ListPermissionFromRoleAction extends BaseController
{
    public function __construct(
        private ListPermissionFromRoleUseCase $listPermissionFromRoleUseCase,
        private ListPermissionFromRoleRequestFactory $factory,
        private ListPermissionFromRoleActionResponder $responder
    ) {
    }

    public function __invoke(ListPermissionFromRoleRequest $request, string $userId): ListPermissionFromRoleActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $user_permissions = $this->listPermissionFromRoleUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($user_permissions);
    }
}
