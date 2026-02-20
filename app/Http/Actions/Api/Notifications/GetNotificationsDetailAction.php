<?php

namespace App\Http\Actions\Api\Notifications;

use App\Domain\Notifications\UseCase\GetNotificationsDetailUseCase;
use App\Domain\Notifications\Factory\GetNotificationsDetailRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Notifications\GetNotificationsDetailRequest;
use App\Http\Resources\Api\Notifications\GetNotificationsDetailActionResource;
use App\Http\Responders\Api\Notifications\GetNotificationsDetailActionResponder;

class GetNotificationsDetailAction extends BaseController
{
    public function __construct(
        private GetNotificationsDetailUseCase $getNotificationsDetailUseCase,
        private GetNotificationsDetailRequestFactory $factory,
        private GetNotificationsDetailActionResponder $responder
    ) {
    }

    public function __invoke(GetNotificationsDetailRequest $request): GetNotificationsDetailActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $notification = $this->getNotificationsDetailUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($notification);
    }
}
