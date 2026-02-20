<?php

namespace App\Http\Actions\Api\FcmToken;

use App\Domain\FcmToken\UseCase\CreateFcmTokenUseCase;
use App\Domain\FcmToken\Factory\CreateFcmTokenRequestFactory;
use App\Http\Requests\Api\FcmToken\CreateFcmTokenRequest;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\FcmToken\ActionResource;
use App\Http\Responders\Api\FcmToken\ActionResponder;

class CreateFcmTokenAction extends BaseController
{
    public function __construct(
        private CreateFcmTokenUseCase $createFcmTokenUseCase,
        private CreateFcmTokenRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(CreateFcmTokenRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $responseEntity = $this->createFcmTokenUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($responseEntity);
    }
}
