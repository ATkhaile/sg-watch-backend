<?php

namespace App\Http\Actions\Api\FcmToken;

use App\Domain\FcmToken\UseCase\GetUserFcmTokensUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\FcmToken\GetUserFcmTokensRequest;
use App\Http\Resources\Api\FcmToken\GetUserFcmTokensActionResource;
use App\Http\Responders\Api\FcmToken\GetUserFcmTokensActionResponder;

class GetUserFcmTokensAction extends BaseController
{
    public function __construct(
        private GetUserFcmTokensUseCase $useCase,
        private GetUserFcmTokensActionResponder $responder
    ) {}

    public function __invoke(GetUserFcmTokensRequest $request, int $userId): GetUserFcmTokensActionResource
    {
        $entity = $this->useCase->__invoke($userId);
        return $this->responder->__invoke($entity);
    }
}
