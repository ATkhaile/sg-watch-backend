<?php
namespace App\Http\Actions\Api\NotificationPush;

use App\Domain\NotificationPush\Factory\GetNotificationPushHistoryRequestFactory;
use App\Domain\NotificationPush\UseCase\GetNotificationPushHistoryUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\NotificationPush\GetNotificationPushHistoryRequest;
use App\Http\Resources\Api\NotificationPush\GetNotificationPushHistoryActionResource;
use App\Http\Responders\Api\NotificationPush\GetNotificationPushHistoryActionResponder;

class GetNotificationPushHistoryAction extends BaseController
{
    public function __construct(
        private GetNotificationPushHistoryUseCase $useCase,
        private GetNotificationPushHistoryRequestFactory $factory,
        private GetNotificationPushHistoryActionResponder $responder
    ) {}

    public function __invoke(GetNotificationPushHistoryRequest $request, int $notification_push_id): GetNotificationPushHistoryActionResource
    {
        $entity = $this->factory->createFromRequest($request);
        $entity = $this->useCase->__invoke($entity, $notification_push_id);
        return $this->responder->__invoke($entity);
    }
}