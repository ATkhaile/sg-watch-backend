<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\UseCase\GetMessagesBetweenUsersUseCase;
use App\Http\Requests\Api\Chat\GetChatHistoryRequest;
use App\Domain\Chat\Factory\GetChatHistoryRequestFactory;
use App\Http\Responders\Api\Chat\GetAllChatHistoryResponder;
use App\Http\Resources\Api\Chat\GetAllChatHistoryResource;

class GetAllChatHistoryAction
{
    public function __construct(
        private GetMessagesBetweenUsersUseCase $getMessagesBetweenUsersUseCase,
        private GetChatHistoryRequestFactory $factory,
        private GetAllChatHistoryResponder $responder
    ) {
    }

    public function __invoke(GetChatHistoryRequest $request): GetAllChatHistoryResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $chatHistorys = $this->getMessagesBetweenUsersUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($chatHistorys);
    }
}
