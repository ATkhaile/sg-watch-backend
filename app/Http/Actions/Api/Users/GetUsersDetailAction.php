<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\UseCase\GetUsersDetailUseCase;
use App\Domain\Users\Factory\GetUsersDetailRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Users\GetUsersDetailActionResource;
use App\Http\Responders\Api\Users\GetUsersDetailActionResponder;
use App\Http\Requests\Api\Users\FindUsersRequest;

class GetUsersDetailAction extends BaseController
{
    public function __construct(
        private GetUsersDetailUseCase $getUsersDetailUseCase,
        private GetUsersDetailRequestFactory $factory,
        private GetUsersDetailActionResponder $responder
    ) {
    }

    public function __invoke(FindUsersRequest $request, string $usersId): GetUsersDetailActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $users = $this->getUsersDetailUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($users);
    }
}
