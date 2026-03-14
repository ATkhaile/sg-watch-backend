<?php

namespace App\Http\Responders\Api\BigSale;

use App\Http\Resources\Api\BigSale\GetPublicBigSaleDetailActionResource;

final class GetPublicBigSaleDetailActionResponder
{
    public function __invoke(array $bigSale): GetPublicBigSaleDetailActionResource
    {
        return new GetPublicBigSaleDetailActionResource(['big_sale' => $bigSale]);
    }
}
