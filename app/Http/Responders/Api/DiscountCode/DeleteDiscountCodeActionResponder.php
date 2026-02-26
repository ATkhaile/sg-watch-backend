<?php

namespace App\Http\Responders\Api\DiscountCode;

use App\Http\Resources\Api\DiscountCode\DeleteDiscountCodeActionResource;

final class DeleteDiscountCodeActionResponder
{
    public function __invoke(array $result): DeleteDiscountCodeActionResource
    {
        return new DeleteDiscountCodeActionResource($result);
    }
}
