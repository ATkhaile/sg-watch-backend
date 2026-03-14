<?php

namespace App\Http\Responders\Api\BigSale;

use App\Http\Resources\Api\BigSale\GetPublicBigSaleListActionResource;

final class GetPublicBigSaleListActionResponder
{
    public function __invoke(array $result): GetPublicBigSaleListActionResource
    {
        return new GetPublicBigSaleListActionResource($result);
    }
}
