<?php

namespace App\Http\Actions\Api\ShopReview;

use App\Domain\ShopReview\UseCase\CreateReviewUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopReview\CreateReviewRequest;
use App\Http\Resources\Api\ShopReview\CreateReviewActionResource;
use App\Http\Responders\Api\ShopReview\CreateReviewActionResponder;

class CreateReviewAction extends BaseController
{
    private CreateReviewUseCase $useCase;
    private CreateReviewActionResponder $responder;

    public function __construct(
        CreateReviewUseCase $useCase,
        CreateReviewActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateReviewRequest $request): CreateReviewActionResource
    {
        $userId = (int) auth()->user()->id;
        $result = $this->useCase->__invoke($userId, $request->validated());

        return $this->responder->__invoke($result);
    }
}
