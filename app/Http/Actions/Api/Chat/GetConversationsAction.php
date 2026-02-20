<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\UseCase\GetConversationsUseCase;
use App\Domain\Chat\Factory\GetConversationsRequestFactory;
use App\Http\Requests\Api\Chat\GetConversationsRequest;
use App\Http\Resources\Api\Chat\GetConversationsResource;
use App\Http\Responders\Api\Chat\GetConversationsResponder;

class GetConversationsAction
{
    public function __construct(
        private GetConversationsResponder $responder,
        private GetConversationsRequestFactory $factory,
        private GetConversationsUseCase $useCase
    ) {
    }

    public function __invoke(GetConversationsRequest $request): GetConversationsResource
    {
        $entity = $this->factory->createFromRequest($request);
        $result = $this->useCase->execute($entity);

        return $this->responder->__invoke($result);
    }
}