<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\AdminDeleteOrderUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopOrder\AdminDeleteOrderActionResource;
use App\Http\Responders\Api\ShopOrder\AdminDeleteOrderActionResponder;
use Illuminate\Http\Request;

class AdminDeleteOrderAction extends BaseController
{
    private AdminDeleteOrderUseCase $useCase;
    private AdminDeleteOrderActionResponder $responder;

    public function __construct(
        AdminDeleteOrderUseCase $useCase,
        AdminDeleteOrderActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, int $id): AdminDeleteOrderActionResource
    {
        $result = $this->useCase->__invoke($id);

        return $this->responder->__invoke($result);
    }
}
