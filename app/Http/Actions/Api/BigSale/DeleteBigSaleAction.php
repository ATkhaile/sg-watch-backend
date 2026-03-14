<?php

namespace App\Http\Actions\Api\BigSale;

use App\Domain\BigSale\UseCase\DeleteBigSaleUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\BigSale\DeleteBigSaleActionResource;
use App\Http\Responders\Api\BigSale\DeleteBigSaleActionResponder;

class DeleteBigSaleAction extends BaseController
{
    private DeleteBigSaleUseCase $useCase;
    private DeleteBigSaleActionResponder $responder;

    public function __construct(
        DeleteBigSaleUseCase $useCase,
        DeleteBigSaleActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteBigSaleActionResource
    {
        $result = $this->useCase->__invoke($id);
        return $this->responder->__invoke($result);
    }
}
