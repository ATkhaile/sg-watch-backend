<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\Factory\GetAvailableUsersRequestFactory;
use App\Domain\Chat\UseCase\GetAvailableUsersUseCase;
use App\Http\Requests\Api\Chat\GetAvailableUsersRequest;
use App\Http\Resources\Api\Chat\GetAvailableUsersResource;
use App\Http\Responders\Api\Chat\GetAvailableUsersResponder;

class GetAvailableUsersAction
{
    public function __construct(
        private GetAvailableUsersResponder $responder,
        private GetAvailableUsersRequestFactory $factory,
        private GetAvailableUsersUseCase $useCase
    ) {
    }

    public function __invoke(GetAvailableUsersRequest $request): GetAvailableUsersResource
    {
        $user = auth()->user();
        $entity = $this->factory->create($request, $user);
        $result = $this->useCase->execute($entity);

        return $this->responder->__invoke($result);
    }
}