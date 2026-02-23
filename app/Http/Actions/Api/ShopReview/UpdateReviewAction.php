<?php

namespace App\Http\Actions\Api\ShopReview;

use App\Domain\ShopReview\UseCase\UpdateReviewUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopReview\UpdateReviewRequest;
use App\Http\Resources\Api\ShopReview\UpdateReviewActionResource;
use App\Http\Responders\Api\ShopReview\UpdateReviewActionResponder;

class UpdateReviewAction extends BaseController
{
    private UpdateReviewUseCase $useCase;
    private UpdateReviewActionResponder $responder;

    public function __construct(
        UpdateReviewUseCase $useCase,
        UpdateReviewActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateReviewRequest $request, int $id): UpdateReviewActionResource
    {
        $userId = (int) auth()->user()->id;
        $result = $this->useCase->__invoke($userId, $id, $request->validated());

        return $this->responder->__invoke($result);
    }
}
