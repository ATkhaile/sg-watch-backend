<?php

namespace App\Http\Actions\Api\NotificationPush;

use App\Domain\NotificationPush\Factory\UpdateNotificationPushRequestFactory;
use App\Domain\NotificationPush\UseCase\UpdateNotificationPushUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\NotificationPush\UpdateNotificationPushRequest;
use App\Http\Resources\Api\NotificationPush\ActionResource;
use App\Http\Responders\Api\NotificationPush\ActionResponder;

class UpdateNotificationPushAction extends BaseController
{
    public function __construct(
        private UpdateNotificationPushUseCase $useCase,
        private UpdateNotificationPushRequestFactory $factory,
        private ActionResponder $responder
    ) {}

    public function __invoke(UpdateNotificationPushRequest $request, int $id): ActionResource
    {
        $entity = $this->factory->createFromRequest($request);
        $status = $this->useCase->__invoke($entity, $id);
        return $this->responder->__invoke($status);
    }
}
