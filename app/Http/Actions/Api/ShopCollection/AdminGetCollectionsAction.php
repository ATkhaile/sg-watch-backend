<?php

namespace App\Http\Actions\Api\ShopCollection;

use App\Domain\ShopCollection\UseCase\AdminGetCollectionsUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopCollection\AdminGetCollectionsActionResource;
use App\Http\Responders\Api\ShopCollection\AdminGetCollectionsActionResponder;

class AdminGetCollectionsAction extends BaseController
{
    private AdminGetCollectionsUseCase $useCase;
    private AdminGetCollectionsActionResponder $responder;

    public function __construct(
        AdminGetCollectionsUseCase $useCase,
        AdminGetCollectionsActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(): AdminGetCollectionsActionResource
    {
        $collections = $this->useCase->__invoke();

        return $this->responder->__invoke($collections);
    }
}
