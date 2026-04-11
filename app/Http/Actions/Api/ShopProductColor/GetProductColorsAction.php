<?php

namespace App\Http\Actions\Api\ShopProductColor;

use App\Domain\ShopProductColor\UseCase\GetProductColorsUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProductColor\GetProductColorsActionResource;
use App\Http\Responders\Api\ShopProductColor\GetProductColorsActionResponder;
use Illuminate\Http\Request;

class GetProductColorsAction extends BaseController
{
    private GetProductColorsUseCase $useCase;
    private GetProductColorsActionResponder $responder;

    public function __construct(
        GetProductColorsUseCase $useCase,
        GetProductColorsActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, int $productId): GetProductColorsActionResource
    {
        $result = $this->useCase->__invoke($productId);

        return $this->responder->__invoke($result);
    }
}
