<?php

namespace App\Http\Actions\Api\FcmToken;

use App\Domain\FcmToken\Factory\DeleteFcmTokenRequestFactory;
use App\Domain\FcmToken\UseCase\DeleteFcmTokenUseCase;
use App\Http\Requests\Api\FcmToken\DeleteFcmTokenRequest;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\FcmToken\ActionResource;
use App\Http\Responders\Api\FcmToken\ActionResponder;

class DeleteFcmTokenAction extends BaseController
{
    public function __construct(
        private DeleteFcmTokenUseCase $deleteFcmTokenUseCase,
        private DeleteFcmTokenRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(DeleteFcmTokenRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $responseEntity = $this->deleteFcmTokenUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($responseEntity);
    }
}
