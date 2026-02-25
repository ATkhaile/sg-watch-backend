<?php

namespace App\Http\Actions\Api\AdminUser;

use App\Domain\AdminUser\UseCase\GetAdminUserListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\AdminUser\GetAdminUserListRequest;
use App\Http\Resources\Api\AdminUser\GetAdminUserListActionResource;
use App\Http\Responders\Api\AdminUser\GetAdminUserListActionResponder;

class GetAdminUserListAction extends BaseController
{
    private GetAdminUserListUseCase $useCase;
    private GetAdminUserListActionResponder $responder;

    public function __construct(
        GetAdminUserListUseCase $useCase,
        GetAdminUserListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetAdminUserListRequest $request): GetAdminUserListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
