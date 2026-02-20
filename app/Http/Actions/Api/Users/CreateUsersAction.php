<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\UseCase\CreateUsersUseCase;
use App\Domain\Users\Factory\CreateUsersRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Users\CreateUsersRequest;
use App\Http\Resources\Api\Users\ActionResource;
use App\Http\Responders\Api\Users\ActionResponder;

class CreateUsersAction extends BaseController
{
    public function __construct(
        private CreateUsersUseCase $createUsersUseCase,
        private CreateUsersRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }


    public function __invoke(CreateUsersRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);

        $statusEntity = $this->createUsersUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
