<?php

namespace App\Http\Actions\Api\ShopCategory;

use App\Domain\ShopCategory\UseCase\DeleteShopCategoryUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopCategory\DeleteShopCategoryActionResource;
use App\Http\Responders\Api\ShopCategory\DeleteShopCategoryActionResponder;

class DeleteShopCategoryAction extends BaseController
{
    private DeleteShopCategoryUseCase $useCase;
    private DeleteShopCategoryActionResponder $responder;

    public function __construct(
        DeleteShopCategoryUseCase $useCase,
        DeleteShopCategoryActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteShopCategoryActionResource
    {
        $result = $this->useCase->__invoke($id);
        return $this->responder->__invoke($result);
    }
}
