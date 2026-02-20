<?php

namespace App\Http\Actions\Api\NotificationPush;

use App\Domain\NotificationPush\Factory\CreateNotificationPushRequestFactory;
use App\Domain\NotificationPush\UseCase\CreateNotificationPushUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\NotificationPush\CreateNotificationPushRequest;
use App\Http\Resources\Api\NotificationPush\ActionResource;
use App\Http\Responders\Api\NotificationPush\ActionResponder;

class CreateNotificationPushAction extends BaseController
{
    public function __construct(
        private CreateNotificationPushUseCase $useCase,
        private CreateNotificationPushRequestFactory $factory,
        private ActionResponder $responder
    ) {}

    public function __invoke(CreateNotificationPushRequest $request): ActionResource
    {
        $entity = $this->factory->createFromRequest($request);
        $status = $this->useCase->__invoke($entity);
        return $this->responder->__invoke($status);
    }
}
