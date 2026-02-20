<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\Factory\GetUserSessionDevicesRequestFactory;
use App\Domain\Users\UseCase\GetUserSessionDevicesUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Users\GetUserSessionDevicesRequest;
use App\Http\Resources\Api\Users\GetUserSessionDevicesResource;

final class GetUserSessionDevicesAction extends BaseController
{
    public function __construct(
        private GetUserSessionDevicesUseCase $useCase,
        private GetUserSessionDevicesRequestFactory $factory
    ) {}

    public function __invoke(GetUserSessionDevicesRequest $request): GetUserSessionDevicesResource
    {
        $entity = $this->factory->createFromRequest($request);
        $result = ($this->useCase)($entity);

        return new GetUserSessionDevicesResource($result);
    }
}
