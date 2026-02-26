<?php

namespace App\Http\Actions\Api\DiscountCode;

use App\Domain\DiscountCode\UseCase\DeleteDiscountCodeUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\DiscountCode\DeleteDiscountCodeActionResource;
use App\Http\Responders\Api\DiscountCode\DeleteDiscountCodeActionResponder;

class DeleteDiscountCodeAction extends BaseController
{
    private DeleteDiscountCodeUseCase $useCase;
    private DeleteDiscountCodeActionResponder $responder;

    public function __construct(
        DeleteDiscountCodeUseCase $useCase,
        DeleteDiscountCodeActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteDiscountCodeActionResource
    {
        $result = $this->useCase->__invoke($id);
        return $this->responder->__invoke($result);
    }
}
