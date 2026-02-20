<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\UpdateRoleUseCase;
use App\Domain\Authorization\Factory\UpdateRoleRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\UpdateRoleRequest;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;

class UpdateRoleDetailAction extends BaseController
{
    public function __construct(
        private UpdateRoleUseCase $updateTagsUseCase,
        private UpdateRoleRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(UpdateRoleRequest $request, string $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->updateTagsUseCase->__invoke($requestEntity, $id);
        return $this->responder->__invoke($statusEntity);
    }
}
