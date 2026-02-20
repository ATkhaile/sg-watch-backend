<?php

namespace App\Http\Actions\Api\FcmToken;

use App\Domain\FcmToken\Factory\UpdateFcmTokenStatusRequestFactory;
use App\Domain\FcmToken\UseCase\UpdateFcmTokenStatusUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\FcmToken\UpdateFcmTokenStatusRequest;
use App\Http\Resources\Api\FcmToken\ActionResource;
use App\Http\Responders\Api\FcmToken\ActionResponder;

class UpdateFcmTokenStatusAction extends BaseController
{
    public function __construct(
        private UpdateFcmTokenStatusUseCase $useCase,
        private UpdateFcmTokenStatusRequestFactory $factory,
        private ActionResponder $responder
    ) {}

    public function __invoke(UpdateFcmTokenStatusRequest $request, int $fcm_token_id): ActionResource
    {
        $reqEntity = $this->factory->createFromRequest($request);
        $status = $this->useCase->__invoke($reqEntity);
        return $this->responder->__invoke($status);
    }
}
