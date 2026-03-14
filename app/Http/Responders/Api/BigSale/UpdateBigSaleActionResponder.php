<?php

namespace App\Http\Responders\Api\BigSale;

use App\Http\Resources\Api\BigSale\UpdateBigSaleActionResource;

final class UpdateBigSaleActionResponder
{
    public function __invoke(array $result): UpdateBigSaleActionResource
    {
        return new UpdateBigSaleActionResource($result);
    }
}
