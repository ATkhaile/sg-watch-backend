<?php

namespace App\Http\Actions\Api\ShopCollection;

use App\Domain\ShopCollection\UseCase\DeleteCollectionUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopCollection\DeleteCollectionActionResource;
use App\Http\Responders\Api\ShopCollection\DeleteCollectionActionResponder;

class DeleteCollectionAction extends BaseController
{
    private DeleteCollectionUseCase $useCase;
    private DeleteCollectionActionResponder $responder;

    public function __construct(
        DeleteCollectionUseCase $useCase,
        DeleteCollectionActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteCollectionActionResource
    {
        $result = $this->useCase->__invoke($id);

        return $this->responder->__invoke($result);
    }
}
