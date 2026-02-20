<?php

namespace App\Http\Actions\Api\Notifications;

use App\Domain\Notifications\UseCase\DeleteNotificationsUseCase;
use App\Domain\Notifications\Factory\DeleteNotificationsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Notifications\ActionResource;
use App\Http\Responders\Api\Notifications\ActionResponder;
use App\Http\Requests\Api\Notifications\DeleteNotificationsRequest;

class DeleteNotificationsAction extends BaseController
{
    public function __construct(
        private DeleteNotificationsUseCase $useCase,
        private DeleteNotificationsRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(DeleteNotificationsRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
