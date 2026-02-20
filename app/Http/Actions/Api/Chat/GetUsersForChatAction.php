<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\Factory\GetUsersForChatRequestFactory;
use App\Domain\Chat\UseCase\GetUsersForChatUseCase;
use App\Http\Requests\Api\Chat\GetUsersForChatRequest;
use App\Http\Resources\Api\Chat\GetUsersForChatResource;
use App\Http\Responders\Api\Chat\GetUsersForChatResponder;

class GetUsersForChatAction
{
    public function __construct(
        private GetUsersForChatResponder $responder,
        private GetUsersForChatRequestFactory $factory,
        private GetUsersForChatUseCase $useCase
    ) {
    }

    public function __invoke(GetUsersForChatRequest $request): GetUsersForChatResource
    {
        $entity = $this->factory->createFromRequest($request);
        $result = $this->useCase->__invoke($entity);

        return $this->responder->__invoke($result);
    }
}