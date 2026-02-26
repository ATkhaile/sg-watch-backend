<?php

namespace App\Http\Responders\Api\DiscountCode;

use App\Http\Resources\Api\DiscountCode\GetDiscountCodeListActionResource;

final class GetDiscountCodeListActionResponder
{
    public function __invoke(array $result): GetDiscountCodeListActionResource
    {
        return new GetDiscountCodeListActionResource($result);
    }
}
