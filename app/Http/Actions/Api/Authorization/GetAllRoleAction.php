<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\GetAllRoleUseCase;
use App\Domain\Authorization\Factory\GetAllRoleRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\GetAllRoleRequest;
use App\Http\Resources\Api\Authorization\GetAllRoleActionResource;
use App\Http\Responders\Api\Authorization\GetAllRoleActionResponder;

class GetAllRoleAction extends BaseController
{
    public function __construct(
        private GetAllRoleUseCase $getAllRoleUseCase,
        private GetAllRoleRequestFactory $factory,
        private GetAllRoleActionResponder $responder
    ) {
    }

    public function __invoke(GetAllRoleRequest $request): GetAllRoleActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $roles = $this->getAllRoleUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($roles);
    }
}
