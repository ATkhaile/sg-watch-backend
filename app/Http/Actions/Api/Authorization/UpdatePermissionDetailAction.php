<?php

namespace App\Http\Actions\Api\Authorization;

use App\Domain\Authorization\UseCase\UpdatePermissionUseCase;
use App\Domain\Authorization\Factory\UpdatePermissionRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Authorization\UpdatePermissionRequest;
use App\Http\Resources\Api\Authorization\ActionResource;
use App\Http\Responders\Api\Authorization\ActionResponder;

class UpdatePermissionDetailAction extends BaseController
{
    public function __construct(
        private UpdatePermissionUseCase $updateeTagsUseCase,
        private UpdatePermissionRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(UpdatePermissionRequest $request, string $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->updateeTagsUseCase->__invoke($requestEntity, $id);
        return $this->responder->__invoke($statusEntity);
    }
}
