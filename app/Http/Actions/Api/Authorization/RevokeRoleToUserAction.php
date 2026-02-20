<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\RevokeRoleToUserUseCase;
use App\Domain\Authorization\Factory\RevokeRoleToUserRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\RevokeRoleToUserRequest;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;

class RevokeRoleToUserAction extends BaseController
{
    public function __construct(
        private RevokeRoleToUserUseCase $useCase,
        private RevokeRoleToUserRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(RevokeRoleToUserRequest $request, string $userId): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request, $userId);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
