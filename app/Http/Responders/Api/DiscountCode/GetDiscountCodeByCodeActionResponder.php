<?php

namespace App\Http\Responders\Api\DiscountCode;

use App\Http\Resources\Api\DiscountCode\GetDiscountCodeByCodeActionResource;

final class GetDiscountCodeByCodeActionResponder
{
    public function __invoke(array $discountCode): GetDiscountCodeByCodeActionResource
    {
        return new GetDiscountCodeByCodeActionResource(['discount_code' => $discountCode]);
    }
}
