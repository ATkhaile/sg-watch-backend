<?php

namespace App\Http\Actions\Api\ShopCollection;

use App\Domain\ShopCollection\UseCase\UpdateCollectionUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopCollection\UpdateCollectionActionResource;
use App\Http\Responders\Api\ShopCollection\UpdateCollectionActionResponder;
use App\Http\Requests\Api\ShopCollection\UpdateCollectionRequest;

class UpdateCollectionAction extends BaseController
{
    private UpdateCollectionUseCase $useCase;
    private UpdateCollectionActionResponder $responder;

    public function __construct(
        UpdateCollectionUseCase $useCase,
        UpdateCollectionActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateCollectionRequest $request, int $id): UpdateCollectionActionResource
    {
        $result = $this->useCase->__invoke($id, $request->validated());

        return $this->responder->__invoke($result);
    }
}
