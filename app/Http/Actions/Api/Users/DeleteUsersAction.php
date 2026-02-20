<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\UseCase\DeleteUsersUseCase;
use App\Domain\Users\Factory\DeleteUsersRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Users\ActionResource;
use App\Http\Responders\Api\Users\ActionResponder;
use App\Http\Requests\Api\Users\DeleteUsersRequest;

class DeleteUsersAction extends BaseController
{
    public function __construct(
        private DeleteUsersUseCase $useCase,
        private DeleteUsersRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(DeleteUsersRequest $request, string $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
