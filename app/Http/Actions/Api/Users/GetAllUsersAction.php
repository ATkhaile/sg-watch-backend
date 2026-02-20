<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\UseCase\GetAllUsersUseCase;
use App\Domain\Users\Factory\GetAllUsersRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Users\GetAllUsersRequest;
use App\Http\Resources\Api\Users\GetAllUsersActionResource;
use App\Http\Responders\Api\Users\GetAllUsersActionResponder;

class GetAllUsersAction extends BaseController
{
    public function __construct(
        private GetAllUsersUseCase $getAllUsersUseCase,
        private GetAllUsersRequestFactory $factory,
        private GetAllUsersActionResponder $responder
    ) {
    }

    public function __invoke(GetAllUsersRequest $request): GetAllUsersActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $users = $this->getAllUsersUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($users);
    }
}
