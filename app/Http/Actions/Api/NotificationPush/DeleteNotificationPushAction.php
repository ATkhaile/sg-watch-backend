<?php

namespace App\Http\Actions\Api\NotificationPush;

use App\Domain\NotificationPush\Factory\DeleteNotificationPushRequestFactory;
use App\Domain\NotificationPush\UseCase\DeleteNotificationPushUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\NotificationPush\DeleteNotificationPushRequest;
use App\Http\Resources\Api\NotificationPush\ActionResource;
use App\Http\Responders\Api\NotificationPush\ActionResponder;

class DeleteNotificationPushAction extends BaseController
{
    public function __construct(
        private DeleteNotificationPushUseCase $useCase,
        private DeleteNotificationPushRequestFactory $factory,
        private ActionResponder $responder
    ) {}

    public function __invoke(DeleteNotificationPushRequest $request, int $id): ActionResource
    {
        $entity  = $this->factory->createFromRequest($request);
        $status  = $this->useCase->__invoke($entity);
        return $this->responder->__invoke($status);
    }
}
