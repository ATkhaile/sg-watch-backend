<?php

namespace App\Http\Actions\Api\ShopCollection;

use App\Domain\ShopCollection\UseCase\AdminGetCollectionDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopCollection\AdminGetCollectionDetailActionResource;
use App\Http\Responders\Api\ShopCollection\AdminGetCollectionDetailActionResponder;

class AdminGetCollectionDetailAction extends BaseController
{
    private AdminGetCollectionDetailUseCase $useCase;
    private AdminGetCollectionDetailActionResponder $responder;

    public function __construct(
        AdminGetCollectionDetailUseCase $useCase,
        AdminGetCollectionDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): AdminGetCollectionDetailActionResource
    {
        $result = $this->useCase->__invoke($id);

        return $this->responder->__invoke($result);
    }
}
