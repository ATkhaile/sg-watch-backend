<?php

namespace App\Http\Actions\Api\ShopReview;

use App\Domain\ShopReview\UseCase\DeleteReviewUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopReview\DeleteReviewActionResource;
use App\Http\Responders\Api\ShopReview\DeleteReviewActionResponder;
use Illuminate\Http\Request;

class DeleteReviewAction extends BaseController
{
    private DeleteReviewUseCase $useCase;
    private DeleteReviewActionResponder $responder;

    public function __construct(
        DeleteReviewUseCase $useCase,
        DeleteReviewActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, int $id): DeleteReviewActionResource
    {
        $userId = (int) auth()->user()->id;
        $result = $this->useCase->__invoke($userId, $id);

        return $this->responder->__invoke($result);
    }
}
