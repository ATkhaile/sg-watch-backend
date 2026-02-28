<?php

namespace App\Http\Actions\Api\ShopBrand;

use App\Domain\ShopBrand\UseCase\UpdateShopBrandUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopBrand\UpdateShopBrandRequest;
use App\Http\Resources\Api\ShopBrand\UpdateShopBrandActionResource;
use App\Http\Responders\Api\ShopBrand\UpdateShopBrandActionResponder;

class UpdateShopBrandAction extends BaseController
{
    private UpdateShopBrandUseCase $useCase;
    private UpdateShopBrandActionResponder $responder;

    public function __construct(
        UpdateShopBrandUseCase $useCase,
        UpdateShopBrandActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateShopBrandRequest $request, int $id): UpdateShopBrandActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $result = $this->useCase->__invoke($id, $data);
        return $this->responder->__invoke($result);
    }
}
