<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\AdminUpdateOrderStatusUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopOrder\AdminUpdateOrderStatusRequest;
use App\Http\Resources\Api\ShopOrder\AdminUpdateOrderStatusActionResource;
use App\Http\Responders\Api\ShopOrder\AdminUpdateOrderStatusActionResponder;

class AdminUpdateOrderStatusAction extends BaseController
{
    private AdminUpdateOrderStatusUseCase $useCase;
    private AdminUpdateOrderStatusActionResponder $responder;

    public function __construct(
        AdminUpdateOrderStatusUseCase $useCase,
        AdminUpdateOrderStatusActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(AdminUpdateOrderStatusRequest $request, int $id): AdminUpdateOrderStatusActionResource
    {
        $validated = $request->validated();
        $status = $validated['status'];
        unset($validated['status']);

        $result = $this->useCase->__invoke($id, $status, $validated);

        return $this->responder->__invoke($result);
    }
}
