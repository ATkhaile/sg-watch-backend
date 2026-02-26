<?php

namespace App\Http\Responders\Api\DiscountCode;

use App\Http\Resources\Api\DiscountCode\GetDiscountCodeDetailActionResource;

final class GetDiscountCodeDetailActionResponder
{
    public function __invoke(array $discountCode): GetDiscountCodeDetailActionResource
    {
        return new GetDiscountCodeDetailActionResource(['discount_code' => $discountCode]);
    }
}
