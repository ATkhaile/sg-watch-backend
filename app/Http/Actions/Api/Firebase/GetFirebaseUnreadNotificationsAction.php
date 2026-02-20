<?php

namespace App\Http\Actions\Api\Firebase;

use App\Domain\Firebase\UseCase\GetFirebaseUnreadNotificationsUseCase;
use App\Domain\Firebase\Factory\GetFirebaseUnreadNotificationsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Firebase\GetFirebaseUnreadNotificationsRequest;
use App\Http\Resources\Api\Firebase\GetFirebaseUnreadNotificationsActionResource;
use App\Http\Responders\Api\Firebase\GetFirebaseUnreadNotificationsActionResponder;

class GetFirebaseUnreadNotificationsAction extends BaseController
{
    public function __construct(
        private GetFirebaseUnreadNotificationsUseCase $getFirebaseUnreadNotificationsUseCase,
        private GetFirebaseUnreadNotificationsRequestFactory $factory,
        private GetFirebaseUnreadNotificationsActionResponder $responder
    ) {
    }

    public function __invoke(GetFirebaseUnreadNotificationsRequest $request): GetFirebaseUnreadNotificationsActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $result = $this->getFirebaseUnreadNotificationsUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($result);
    }
}
