<?php

namespace App\Http\Actions\Api\NotificationPush;

use App\Domain\NotificationPush\Factory\GetAllNotificationPushsRequestFactory;
use App\Domain\NotificationPush\UseCase\GetAllNotificationPushsUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\NotificationPush\GetAllNotificationPushsRequest;
use App\Http\Resources\Api\NotificationPush\GetAllNotificationPushsActionResource;
use App\Http\Responders\Api\NotificationPush\GetAllNotificationPushsActionResponder;

class GetAllNotificationPushsAction extends BaseController
{
    public function __construct(
        private GetAllNotificationPushsUseCase $useCase,
        private GetAllNotificationPushsRequestFactory $factory,
        private GetAllNotificationPushsActionResponder $responder
    ) {}

    public function __invoke(GetAllNotificationPushsRequest $request): GetAllNotificationPushsActionResource
    {
        $entity = $this->factory->createFromRequest($request);
        $list   = $this->useCase->__invoke($entity);
        return $this->responder->__invoke($list);
    }
}
