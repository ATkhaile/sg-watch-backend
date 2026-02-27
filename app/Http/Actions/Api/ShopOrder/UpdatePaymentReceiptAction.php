<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\UpdatePaymentReceiptUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopOrder\UpdatePaymentReceiptRequest;
use App\Http\Resources\Api\ShopOrder\UpdatePaymentReceiptActionResource;
use App\Http\Responders\Api\ShopOrder\UpdatePaymentReceiptActionResponder;

class UpdatePaymentReceiptAction extends BaseController
{
    private UpdatePaymentReceiptUseCase $useCase;
    private UpdatePaymentReceiptActionResponder $responder;

    public function __construct(
        UpdatePaymentReceiptUseCase $useCase,
        UpdatePaymentReceiptActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdatePaymentReceiptRequest $request, int $id): UpdatePaymentReceiptActionResource
    {
        $userId = (int) auth()->user()->id;
        $paymentReceipt = $request->file('payment_receipt');
        $result = $this->useCase->__invoke($userId, $id, $paymentReceipt);

        return $this->responder->__invoke($result);
    }
}
