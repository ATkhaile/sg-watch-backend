<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\ListPermissionFromUserUseCase;
use App\Domain\Authorization\Factory\ListPermissionFromUserRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Authorization\ListPermissionFromUserActionResource;
use App\Http\Responders\Api\Authorization\ListPermissionFromUserActionResponder;
use App\Http\Requests\Api\Authorization\ListPermissionFromUserRequest;

class ListPermissionFromUserAction extends BaseController
{
    public function __construct(
        private ListPermissionFromUserUseCase $listPermissionFromUserUseCase,
        private ListPermissionFromUserRequestFactory $factory,
        private ListPermissionFromUserActionResponder $responder
    ) {
    }

    public function __invoke(ListPermissionFromUserRequest $request, string $userId): ListPermissionFromUserActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $user_permissions = $this->listPermissionFromUserUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($user_permissions);
    }
}
