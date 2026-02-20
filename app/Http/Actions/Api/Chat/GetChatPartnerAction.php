<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\Factory\GetChatPartnerRequestFactory;
use App\Domain\Chat\UseCase\GetChatPartnerUseCase;
use App\Http\Requests\Api\Chat\GetChatPartnerRequest;
use App\Http\Resources\Api\Chat\GetChatPartnerResource;
use App\Http\Responders\Api\Chat\GetChatPartnerResponder;

class GetChatPartnerAction
{
    public function __construct(
        private GetChatPartnerResponder $responder,
        private GetChatPartnerRequestFactory $factory,
        private GetChatPartnerUseCase $useCase
    ) {
    }

    public function __invoke(GetChatPartnerRequest $request): GetChatPartnerResource
    {
        $user = auth()->user();
        $entity = $this->factory->create($request, $user);
        $result = $this->useCase->execute($entity);

        return $this->responder->__invoke($result);
    }
}
