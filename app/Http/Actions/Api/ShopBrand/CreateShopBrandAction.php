<?php

namespace App\Http\Actions\Api\ShopBrand;

use App\Domain\ShopBrand\UseCase\CreateShopBrandUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopBrand\CreateShopBrandRequest;
use App\Http\Resources\Api\ShopBrand\CreateShopBrandActionResource;
use App\Http\Responders\Api\ShopBrand\CreateShopBrandActionResponder;

class CreateShopBrandAction extends BaseController
{
    private CreateShopBrandUseCase $useCase;
    private CreateShopBrandActionResponder $responder;

    public function __construct(
        CreateShopBrandUseCase $useCase,
        CreateShopBrandActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateShopBrandRequest $request): CreateShopBrandActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $result = $this->useCase->__invoke($data);
        return $this->responder->__invoke($result);
    }
}
