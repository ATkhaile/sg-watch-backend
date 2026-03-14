<?php

namespace App\Http\Responders\Api\BigSale;

use App\Http\Resources\Api\BigSale\CreateBigSaleActionResource;

final class CreateBigSaleActionResponder
{
    public function __invoke(array $result): CreateBigSaleActionResource
    {
        return new CreateBigSaleActionResource($result);
    }
}
