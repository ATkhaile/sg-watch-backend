<?php

namespace App\Http\Actions\Api\ShopProductColor;

use App\Domain\ShopProductColor\UseCase\DeleteProductColorUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopProductColor\DeleteProductColorActionResource;
use App\Http\Responders\Api\ShopProductColor\DeleteProductColorActionResponder;
use Illuminate\Http\Request;

class DeleteProductColorAction extends BaseController
{
    private DeleteProductColorUseCase $useCase;
    private DeleteProductColorActionResponder $responder;

    public function __construct(
        DeleteProductColorUseCase $useCase,
        DeleteProductColorActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, int $id): DeleteProductColorActionResource
    {
        $result = $this->useCase->__invoke($id);

        return $this->responder->__invoke($result);
    }
}
