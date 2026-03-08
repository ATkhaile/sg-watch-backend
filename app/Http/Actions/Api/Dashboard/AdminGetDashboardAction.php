<?php

namespace App\Http\Actions\Api\Dashboard;

use App\Domain\Dashboard\UseCase\AdminGetDashboardUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Dashboard\AdminGetDashboardRequest;
use App\Http\Resources\Api\Dashboard\AdminGetDashboardActionResource;
use App\Http\Responders\Api\Dashboard\AdminGetDashboardActionResponder;

class AdminGetDashboardAction extends BaseController
{
    private AdminGetDashboardUseCase $useCase;
    private AdminGetDashboardActionResponder $responder;

    public function __construct(
        AdminGetDashboardUseCase $useCase,
        AdminGetDashboardActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(AdminGetDashboardRequest $request): AdminGetDashboardActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
