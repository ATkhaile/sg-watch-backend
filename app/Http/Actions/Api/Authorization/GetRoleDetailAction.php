<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\GetRoleDetailUseCase;
use App\Domain\Authorization\Factory\GetRoleDetailRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Authorization\GetRoleDetailActionResource;
use App\Http\Responders\Api\Authorization\GetRoleDetailActionResponder;
use App\Http\Requests\Api\Authorization\FindRoleRequest;

class GetRoleDetailAction extends BaseController
{
    public function __construct(
        private GetRoleDetailUseCase $getRoleDetailUseCase,
        private GetRoleDetailRequestFactory $factory,
        private GetRoleDetailActionResponder $responder
    ) {
    }

    public function __invoke(FindRoleRequest $request, string $roleId): GetRoleDetailActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $role = $this->getRoleDetailUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($role);
    }
}
