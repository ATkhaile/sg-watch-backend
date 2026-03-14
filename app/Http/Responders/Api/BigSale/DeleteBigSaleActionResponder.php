<?php

namespace App\Http\Responders\Api\BigSale;

use App\Http\Resources\Api\BigSale\DeleteBigSaleActionResource;

final class DeleteBigSaleActionResponder
{
    public function __invoke(array $result): DeleteBigSaleActionResource
    {
        return new DeleteBigSaleActionResource($result);
    }
}
