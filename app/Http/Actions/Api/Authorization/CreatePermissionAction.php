<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\CreatePermissionUseCase;
use App\Domain\Authorization\Factory\CreatePermissionRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\CreatePermissionRequest;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;

class CreatePermissionAction extends BaseController
{
    public function __construct(
        private CreatePermissionUseCase $createPermissionUseCase,
        private CreatePermissionRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }


    public function __invoke(CreatePermissionRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);

        $statusEntity = $this->createPermissionUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
