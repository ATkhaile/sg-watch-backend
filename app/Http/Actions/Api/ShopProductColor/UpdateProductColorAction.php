<?php

namespace App\Http\Actions\Api\ShopProductColor;

use App\Domain\ShopProductColor\UseCase\UpdateProductColorUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopProductColor\UpdateProductColorRequest;
use App\Http\Resources\Api\ShopProductColor\UpdateProductColorActionResource;
use App\Http\Responders\Api\ShopProductColor\UpdateProductColorActionResponder;

class UpdateProductColorAction extends BaseController
{
    private UpdateProductColorUseCase $useCase;
    private UpdateProductColorActionResponder $responder;

    public function __construct(
        UpdateProductColorUseCase $useCase,
        UpdateProductColorActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateProductColorRequest $request, int $id): UpdateProductColorActionResource
    {
        $result = $this->useCase->__invoke($id, $request->validated());

        return $this->responder->__invoke($result);
    }
}
