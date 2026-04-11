<?php

namespace App\Http\Actions\Api\ShopProductColor;

use App\Domain\ShopProductColor\UseCase\CreateProductColorUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopProductColor\CreateProductColorRequest;
use App\Http\Resources\Api\ShopProductColor\CreateProductColorActionResource;
use App\Http\Responders\Api\ShopProductColor\CreateProductColorActionResponder;

class CreateProductColorAction extends BaseController
{
    private CreateProductColorUseCase $useCase;
    private CreateProductColorActionResponder $responder;

    public function __construct(
        CreateProductColorUseCase $useCase,
        CreateProductColorActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateProductColorRequest $request): CreateProductColorActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
