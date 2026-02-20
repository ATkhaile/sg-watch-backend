<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\UseCase\UpdateUsersUseCase;
use App\Domain\Users\Factory\UpdateUsersRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Users\UpdateUsersRequest;
use App\Http\Resources\Api\Users\ActionResource;
use App\Http\Responders\Api\Users\ActionResponder;

class UpdateUsersDetailAction extends BaseController
{
    public function __construct(
        private UpdateUsersUseCase $updateUsersUseCase,
        private UpdateUsersRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(UpdateUsersRequest $request, string $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->updateUsersUseCase->__invoke($requestEntity, $id);
        return $this->responder->__invoke($statusEntity);
    }
}
