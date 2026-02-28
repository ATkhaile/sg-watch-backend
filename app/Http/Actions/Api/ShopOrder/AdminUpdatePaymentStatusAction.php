<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\AdminUpdatePaymentStatusUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopOrder\AdminUpdatePaymentStatusRequest;
use App\Http\Resources\Api\ShopOrder\AdminUpdatePaymentStatusActionResource;
use App\Http\Responders\Api\ShopOrder\AdminUpdatePaymentStatusActionResponder;

class AdminUpdatePaymentStatusAction extends BaseController
{
    private AdminUpdatePaymentStatusUseCase $useCase;
    private AdminUpdatePaymentStatusActionResponder $responder;

    public function __construct(
        AdminUpdatePaymentStatusUseCase $useCase,
        AdminUpdatePaymentStatusActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(AdminUpdatePaymentStatusRequest $request, int $id): AdminUpdatePaymentStatusActionResource
    {
        $paymentStatus = $request->validated()['payment_status'];
        $result = $this->useCase->__invoke($id, $paymentStatus);
        return $this->responder->__invoke($result);
    }
}
