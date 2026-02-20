<?php

namespace App\Http\Actions\Api\Notifications;

use App\Domain\Notifications\UseCase\CreateNotificationsUseCase;
use App\Domain\Notifications\Factory\CreateNotificationsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Notifications\CreateNotificationsRequest;
use App\Http\Resources\Api\Notifications\ActionResource;
use App\Http\Responders\Api\Notifications\ActionResponder;

class CreateNotificationsAction extends BaseController
{
    public function __construct(
        private CreateNotificationsUseCase $createNotificationsUseCase,
        private CreateNotificationsRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(CreateNotificationsRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->createNotificationsUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
