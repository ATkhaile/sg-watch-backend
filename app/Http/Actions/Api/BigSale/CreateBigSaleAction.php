<?php

namespace App\Http\Actions\Api\BigSale;

use App\Domain\BigSale\UseCase\CreateBigSaleUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\BigSale\CreateBigSaleRequest;
use App\Http\Resources\Api\BigSale\CreateBigSaleActionResource;
use App\Http\Responders\Api\BigSale\CreateBigSaleActionResponder;

class CreateBigSaleAction extends BaseController
{
    private CreateBigSaleUseCase $useCase;
    private CreateBigSaleActionResponder $responder;

    public function __construct(
        CreateBigSaleUseCase $useCase,
        CreateBigSaleActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateBigSaleRequest $request): CreateBigSaleActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('media')) {
            $data['media'] = $request->file('media');
        }
        $result = $this->useCase->__invoke($data);
        return $this->responder->__invoke($result);
    }
}
