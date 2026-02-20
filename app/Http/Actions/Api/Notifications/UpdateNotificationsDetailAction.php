<?php

namespace App\Http\Actions\Api\Notifications;

use App\Domain\Notifications\UseCase\UpdateNotificationsUseCase;
use App\Domain\Notifications\Factory\UpdateNotificationsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Notifications\UpdateNotificationsRequest;
use App\Http\Resources\Api\Notifications\ActionResource;
use App\Http\Responders\Api\Notifications\ActionResponder;

class UpdateNotificationsDetailAction extends BaseController
{
    public function __construct(
        private UpdateNotificationsUseCase $updateNotificationsUseCase,
        private UpdateNotificationsRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(UpdateNotificationsRequest $request, int $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->updateNotificationsUseCase->__invoke($requestEntity, $id);
        return $this->responder->__invoke($statusEntity);
    }
}
