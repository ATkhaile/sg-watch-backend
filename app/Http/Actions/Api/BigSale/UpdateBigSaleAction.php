<?php

namespace App\Http\Actions\Api\BigSale;

use App\Domain\BigSale\UseCase\UpdateBigSaleUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\BigSale\UpdateBigSaleRequest;
use App\Http\Resources\Api\BigSale\UpdateBigSaleActionResource;
use App\Http\Responders\Api\BigSale\UpdateBigSaleActionResponder;

class UpdateBigSaleAction extends BaseController
{
    private UpdateBigSaleUseCase $useCase;
    private UpdateBigSaleActionResponder $responder;

    public function __construct(
        UpdateBigSaleUseCase $useCase,
        UpdateBigSaleActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateBigSaleRequest $request, int $id): UpdateBigSaleActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('media')) {
            $data['media'] = $request->file('media');
        }
        $result = $this->useCase->__invoke($id, $data);
        return $this->responder->__invoke($result);
    }
}
