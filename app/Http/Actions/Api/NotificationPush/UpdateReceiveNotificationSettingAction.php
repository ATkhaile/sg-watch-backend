<?php

namespace App\Http\Actions\Api\NotificationPush;

use App\Domain\NotificationPush\Factory\UpdateReceiveNotificationSettingRequestFactory;
use App\Domain\NotificationPush\UseCase\UpdateReceiveNotificationSettingUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\NotificationPush\UpdateReceiveNotificationRequest;
use App\Http\Resources\Api\NotificationPush\ActionResource;
use App\Http\Responders\Api\NotificationPush\ActionResponder;

class UpdateReceiveNotificationSettingAction extends BaseController
{
    public function __construct(
        private UpdateReceiveNotificationSettingUseCase $useCase,
        private UpdateReceiveNotificationSettingRequestFactory $factory,
        private ActionResponder $responder
    ) {}

    public function __invoke(UpdateReceiveNotificationRequest $request): ActionResource {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity  = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}