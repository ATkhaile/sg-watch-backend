<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\AdminUpdatePaymentStatusActionResource;

final class AdminUpdatePaymentStatusActionResponder
{
    public function __invoke(array $result): AdminUpdatePaymentStatusActionResource
    {
        return new AdminUpdatePaymentStatusActionResource($result);
    }
}
