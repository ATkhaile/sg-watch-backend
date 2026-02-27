<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\UpdatePaymentReceiptActionResource;

final class UpdatePaymentReceiptActionResponder
{
    public function __invoke(array $result): UpdatePaymentReceiptActionResource
    {
        return new UpdatePaymentReceiptActionResource($result);
    }
}
