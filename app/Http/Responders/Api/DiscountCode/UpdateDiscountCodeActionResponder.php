<?php

namespace App\Http\Responders\Api\DiscountCode;

use App\Http\Resources\Api\DiscountCode\UpdateDiscountCodeActionResource;

final class UpdateDiscountCodeActionResponder
{
    public function __invoke(array $result): UpdateDiscountCodeActionResource
    {
        return new UpdateDiscountCodeActionResource($result);
    }
}
