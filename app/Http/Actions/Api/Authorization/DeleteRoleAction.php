<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\DeleteRoleUseCase;
use App\Domain\Authorization\Factory\DeleteRoleRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;
use App\Http\Requests\Api\Authorization\DeleteRoleRequest;

class DeleteRoleAction extends BaseController
{
    public function __construct(
        private DeleteRoleUseCase $useCase,
        private DeleteRoleRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(DeleteRoleRequest $request, string $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
