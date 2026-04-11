<?php

namespace App\Http\Responders\Api\ShopProductColor;

use App\Http\Resources\Api\ShopProductColor\DeleteProductColorActionResource;

final class DeleteProductColorActionResponder
{
    public function __invoke(array $result): DeleteProductColorActionResource
    {
        return new DeleteProductColorActionResource($result);
    }
}
