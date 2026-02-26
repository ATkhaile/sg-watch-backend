<?php

namespace App\Http\Actions\Api\DiscountCode;

use App\Domain\DiscountCode\UseCase\CreateDiscountCodeUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\DiscountCode\CreateDiscountCodeRequest;
use App\Http\Resources\Api\DiscountCode\CreateDiscountCodeActionResource;
use App\Http\Responders\Api\DiscountCode\CreateDiscountCodeActionResponder;

class CreateDiscountCodeAction extends BaseController
{
    private CreateDiscountCodeUseCase $useCase;
    private CreateDiscountCodeActionResponder $responder;

    public function __construct(
        CreateDiscountCodeUseCase $useCase,
        CreateDiscountCodeActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateDiscountCodeRequest $request): CreateDiscountCodeActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
