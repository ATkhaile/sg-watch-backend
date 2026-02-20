<?php

namespace App\Http\Actions\Api\Notifications;

use App\Domain\Notifications\UseCase\GetAllNotificationsUseCase;
use App\Domain\Notifications\Factory\GetAllNotificationsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Notifications\GetAllNotificationsRequest;
use App\Http\Resources\Api\Notifications\GetAllNotificationsActionResource;
use App\Http\Responders\Api\Notifications\GetAllNotificationsActionResponder;

class GetAllNotificationsAction extends BaseController
{
    public function __construct(
        private GetAllNotificationsUseCase $getAllNotificationsUseCase,
        private GetAllNotificationsRequestFactory $factory,
        private GetAllNotificationsActionResponder $responder
    ) {
    }

    public function __invoke(GetAllNotificationsRequest $request): GetAllNotificationsActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $notifications = $this->getAllNotificationsUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($notifications);
    }
}
