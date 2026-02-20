<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\ListRoleFromUserUseCase;
use App\Domain\Authorization\Factory\ListRoleFromUserRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Authorization\ListRoleFromUserActionResource;
use App\Http\Responders\Api\Authorization\ListRoleFromUserActionResponder;
use App\Http\Requests\Api\Authorization\ListRoleFromUserRequest;

class ListRoleFromUserAction extends BaseController
{
    public function __construct(
        private ListRoleFromUserUseCase $listRoleFromUserUseCase,
        private ListRoleFromUserRequestFactory $factory,
        private ListRoleFromUserActionResponder $responder
    ) {
    }

    public function __invoke(ListRoleFromUserRequest $request, string $userId): ListRoleFromUserActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $user_roles = $this->listRoleFromUserUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($user_roles);
    }
}
