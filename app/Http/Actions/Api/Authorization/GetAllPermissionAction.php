<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\GetAllPermissionUseCase;
use App\Domain\Authorization\Factory\GetAllPermissionRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\GetAllPermissionRequest;
use App\Http\Resources\Api\Authorization\GetAllPermissionActionResource;
use App\Http\Responders\Api\Authorization\GetAllPermissionActionResponder;

class GetAllPermissionAction extends BaseController
{
    public function __construct(
        private GetAllPermissionUseCase $getAllPermissionUseCase,
        private GetAllPermissionRequestFactory $factory,
        private GetAllPermissionActionResponder $responder
    ) {
    }

    public function __invoke(GetAllPermissionRequest $request): GetAllPermissionActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $permissions = $this->getAllPermissionUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($permissions);
    }
}
