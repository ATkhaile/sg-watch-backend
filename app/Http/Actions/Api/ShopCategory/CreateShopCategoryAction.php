<?php

namespace App\Http\Actions\Api\ShopCategory;

use App\Domain\ShopCategory\UseCase\CreateShopCategoryUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopCategory\CreateShopCategoryRequest;
use App\Http\Resources\Api\ShopCategory\CreateShopCategoryActionResource;
use App\Http\Responders\Api\ShopCategory\CreateShopCategoryActionResponder;

class CreateShopCategoryAction extends BaseController
{
    private CreateShopCategoryUseCase $useCase;
    private CreateShopCategoryActionResponder $responder;

    public function __construct(
        CreateShopCategoryUseCase $useCase,
        CreateShopCategoryActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateShopCategoryRequest $request): CreateShopCategoryActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $result = $this->useCase->__invoke($data);
        return $this->responder->__invoke($result);
    }
}
