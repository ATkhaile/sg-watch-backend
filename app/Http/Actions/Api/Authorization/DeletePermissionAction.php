<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\DeletePermissionUseCase;
use App\Domain\Authorization\Factory\DeletePermissionRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;
use App\Http\Requests\Api\Authorization\DeletePermissionRequest;

class DeletePermissionAction extends BaseController
{
    public function __construct(
        private DeletePermissionUseCase $useCase,
        private DeletePermissionRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(DeletePermissionRequest $request, string $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
