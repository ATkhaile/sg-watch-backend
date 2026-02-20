<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\RevokePermissionToUserUseCase;
use App\Domain\Authorization\Factory\RevokePermissionToUserRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\RevokePermissionToUserRequest;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;

class RevokePermissionToUserAction extends BaseController
{
    public function __construct(
        private RevokePermissionToUserUseCase $useCase,
        private RevokePermissionToUserRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(RevokePermissionToUserRequest $request, string $userId): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request, $userId);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
