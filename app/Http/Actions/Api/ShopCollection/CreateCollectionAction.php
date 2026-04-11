<?php

namespace App\Http\Actions\Api\ShopCollection;

use App\Domain\ShopCollection\UseCase\CreateCollectionUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopCollection\CreateCollectionRequest;
use App\Http\Resources\Api\ShopCollection\CreateCollectionActionResource;
use App\Http\Responders\Api\ShopCollection\CreateCollectionActionResponder;

class CreateCollectionAction extends BaseController
{
    private CreateCollectionUseCase $useCase;
    private CreateCollectionActionResponder $responder;

    public function __construct(
        CreateCollectionUseCase $useCase,
        CreateCollectionActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateCollectionRequest $request): CreateCollectionActionResource
    {
        $result = $this->useCase->__invoke($request->validated());

        return $this->responder->__invoke($result);
    }
}
