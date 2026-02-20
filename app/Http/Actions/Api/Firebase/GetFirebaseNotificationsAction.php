<?php

namespace App\Http\Actions\Api\Firebase;

use App\Domain\Firebase\UseCase\GetFirebaseNotificationsUseCase;
use App\Domain\Firebase\Factory\GetFirebaseNotificationsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Firebase\GetFirebaseNotificationsRequest;
use App\Http\Resources\Api\Firebase\GetFirebaseNotificationsActionResource;
use App\Http\Responders\Api\Firebase\GetFirebaseNotificationsActionResponder;

class GetFirebaseNotificationsAction extends BaseController
{
    public function __construct(
        private GetFirebaseNotificationsUseCase $getFirebaseNotificationsUseCase,
        private GetFirebaseNotificationsRequestFactory $factory,
        private GetFirebaseNotificationsActionResponder $responder
    ) {
    }

    public function __invoke(GetFirebaseNotificationsRequest $request): GetFirebaseNotificationsActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $notifications = $this->getFirebaseNotificationsUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($notifications);
    }
}
