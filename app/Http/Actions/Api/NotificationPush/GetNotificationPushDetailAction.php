<?php 

namespace App\Http\Actions\Api\NotificationPush;

use App\Domain\NotificationPush\Factory\GetNotificationPushDetailRequestFactory;
use App\Domain\NotificationPush\UseCase\GetNotificationPushDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\NotificationPush\GetNotificationPushDetailRequest;
use App\Http\Resources\Api\NotificationPush\GetNotificationPushDetailActionResource;
use App\Http\Responders\Api\NotificationPush\GetNotificationPushDetailActionResponder;

class GetNotificationPushDetailAction extends BaseController
{
    public function __construct(
        private GetNotificationPushDetailUseCase $useCase,
        private GetNotificationPushDetailRequestFactory $factory,
        private GetNotificationPushDetailActionResponder $responder
    ) {}

    public function __invoke(GetNotificationPushDetailRequest $request, int $id): GetNotificationPushDetailActionResource
    {
        $entity = $this->factory->createFromRequest($request);
        $detail = $this->useCase->__invoke($entity);
        return $this->responder->__invoke($detail);
    }
}
