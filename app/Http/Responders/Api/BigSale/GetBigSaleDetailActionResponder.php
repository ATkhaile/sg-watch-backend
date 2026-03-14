<?php

namespace App\Http\Responders\Api\BigSale;

use App\Http\Resources\Api\BigSale\GetBigSaleDetailActionResource;

final class GetBigSaleDetailActionResponder
{
    public function __invoke(array $bigSale): GetBigSaleDetailActionResource
    {
        return new GetBigSaleDetailActionResource(['big_sale' => $bigSale]);
    }
}
