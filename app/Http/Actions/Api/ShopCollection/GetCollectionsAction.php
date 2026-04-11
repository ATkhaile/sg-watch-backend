<?php

namespace App\Http\Actions\Api\ShopCollection;

use App\Domain\ShopCollection\UseCase\GetCollectionsUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopCollection\GetCollectionsActionResource;
use App\Http\Responders\Api\ShopCollection\GetCollectionsActionResponder;

class GetCollectionsAction extends BaseController
{
    private GetCollectionsUseCase $useCase;
    private GetCollectionsActionResponder $responder;

    public function __construct(
        GetCollectionsUseCase $useCase,
        GetCollectionsActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(): GetCollectionsActionResource
    {
        $collections = $this->useCase->__invoke();

        return $this->responder->__invoke($collections);
    }
}
