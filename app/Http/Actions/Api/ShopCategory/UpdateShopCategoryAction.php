<?php

namespace App\Http\Actions\Api\ShopCategory;

use App\Domain\ShopCategory\UseCase\UpdateShopCategoryUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopCategory\UpdateShopCategoryRequest;
use App\Http\Resources\Api\ShopCategory\UpdateShopCategoryActionResource;
use App\Http\Responders\Api\ShopCategory\UpdateShopCategoryActionResponder;

class UpdateShopCategoryAction extends BaseController
{
    private UpdateShopCategoryUseCase $useCase;
    private UpdateShopCategoryActionResponder $responder;

    public function __construct(
        UpdateShopCategoryUseCase $useCase,
        UpdateShopCategoryActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateShopCategoryRequest $request, int $id): UpdateShopCategoryActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $result = $this->useCase->__invoke($id, $data);
        return $this->responder->__invoke($result);
    }
}
