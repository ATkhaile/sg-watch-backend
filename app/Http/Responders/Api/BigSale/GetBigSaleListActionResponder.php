<?php

namespace App\Http\Responders\Api\BigSale;

use App\Http\Resources\Api\BigSale\GetBigSaleListActionResource;

final class GetBigSaleListActionResponder
{
    public function __invoke(array $result): GetBigSaleListActionResource
    {
        return new GetBigSaleListActionResource($result);
    }
}
