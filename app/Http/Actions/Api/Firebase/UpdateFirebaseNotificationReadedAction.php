<?php

namespace App\Http\Actions\Api\Firebase;

use App\Domain\Firebase\UseCase\UpdateFirebaseNotificationReadedUseCase;
use App\Domain\Firebase\Factory\UpdateFirebaseNotificationReadedRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Firebase\UpdateFirebaseNotificationReadedRequest;
use App\Http\Resources\Api\Firebase\ActionResource;
use App\Http\Responders\Api\Firebase\ActionResponder;

class UpdateFirebaseNotificationReadedAction extends BaseController
{
    public function __construct(
        private UpdateFirebaseNotificationReadedUseCase $updateFirebaseNotificationReadedUseCase,
        private UpdateFirebaseNotificationReadedRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(UpdateFirebaseNotificationReadedRequest $request, int $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $result = $this->updateFirebaseNotificationReadedUseCase->__invoke($requestEntity, $id);
        return $this->responder->__invoke($result);
    }
}
