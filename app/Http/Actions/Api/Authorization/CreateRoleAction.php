<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\CreateRoleUseCase;
use App\Domain\Authorization\Factory\CreateRoleRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\CreateRoleRequest;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;

class CreateRoleAction extends BaseController
{
    public function __construct(
        private CreateRoleUseCase $createRoleUseCase,
        private CreateRoleRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }


    public function __invoke(CreateRoleRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);

        $statusEntity = $this->createRoleUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
