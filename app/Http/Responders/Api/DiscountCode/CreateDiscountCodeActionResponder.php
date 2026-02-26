<?php

namespace App\Http\Responders\Api\DiscountCode;

use App\Http\Resources\Api\DiscountCode\CreateDiscountCodeActionResource;

final class CreateDiscountCodeActionResponder
{
    public function __invoke(array $result): CreateDiscountCodeActionResource
    {
        return new CreateDiscountCodeActionResource($result);
    }
}
