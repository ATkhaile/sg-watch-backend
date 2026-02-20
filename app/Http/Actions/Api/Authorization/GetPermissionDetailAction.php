<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\GetPermissionDetailUseCase;
use App\Domain\Authorization\Factory\GetPermissionDetailRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Authorization\GetPermissionDetailActionResource;
use App\Http\Responders\Api\Authorization\GetPermissionDetailActionResponder;
use App\Http\Requests\Api\Authorization\FindPermissionRequest;

class GetPermissionDetailAction extends BaseController
{
    public function __construct(
        private GetPermissionDetailUseCase $getPermissionDetailUseCase,
        private GetPermissionDetailRequestFactory $factory,
        private GetPermissionDetailActionResponder $responder
    ) {
    }

    public function __invoke(FindPermissionRequest $request, string $permissionId): GetPermissionDetailActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $permission = $this->getPermissionDetailUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($permission);
    }
}
