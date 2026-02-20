<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\Factory\DeleteUserSessionDeviceRequestFactory;
use App\Domain\Users\UseCase\DeleteUserSessionDeviceUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Users\DeleteUserSessionDeviceRequest;
use App\Http\Resources\Api\Users\ActionResource;
use App\Http\Responders\Api\Users\ActionResponder;

class DeleteUserSessionDeviceAction extends BaseController
{
    public function __construct(
        private DeleteUserSessionDeviceUseCase $useCase,
        private DeleteUserSessionDeviceRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(DeleteUserSessionDeviceRequest $request): ActionResource
    {
        $entity = $this->factory->createFromRequest($request);
        $statusEntity = ($this->useCase)($entity);

        return ($this->responder)($statusEntity);
    }
}
