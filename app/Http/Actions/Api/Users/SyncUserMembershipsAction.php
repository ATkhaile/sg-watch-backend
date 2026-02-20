<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\UseCase\SyncMembershipsUseCase;
use App\Domain\Users\Factory\SyncMembershipsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Users\SyncUserMembershipsActionResource;
use App\Http\Responders\Api\Users\SyncUserMembershipsActionResponder;
use Illuminate\Http\Request;

class SyncUserMembershipsAction extends BaseController
{
    public function __construct(
        private SyncMembershipsUseCase $useCase,
        private SyncMembershipsRequestFactory $factory,
        private SyncUserMembershipsActionResponder $responder
    ) {}

    public function __invoke(Request $request, int $id): SyncUserMembershipsActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request, $id);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
