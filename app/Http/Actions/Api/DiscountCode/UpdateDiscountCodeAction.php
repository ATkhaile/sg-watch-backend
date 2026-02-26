<?php

namespace App\Http\Actions\Api\DiscountCode;

use App\Domain\DiscountCode\UseCase\UpdateDiscountCodeUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\DiscountCode\UpdateDiscountCodeRequest;
use App\Http\Resources\Api\DiscountCode\UpdateDiscountCodeActionResource;
use App\Http\Responders\Api\DiscountCode\UpdateDiscountCodeActionResponder;

class UpdateDiscountCodeAction extends BaseController
{
    private UpdateDiscountCodeUseCase $useCase;
    private UpdateDiscountCodeActionResponder $responder;

    public function __construct(
        UpdateDiscountCodeUseCase $useCase,
        UpdateDiscountCodeActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateDiscountCodeRequest $request, int $id): UpdateDiscountCodeActionResource
    {
        $result = $this->useCase->__invoke($id, $request->validated());
        return $this->responder->__invoke($result);
    }
}
