<?php

namespace App\Http\Actions\Api\ShopReview;

use App\Domain\ShopReview\UseCase\GetReviewDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopReview\GetReviewDetailActionResource;
use App\Http\Responders\Api\ShopReview\GetReviewDetailActionResponder;

class GetReviewDetailAction extends BaseController
{
    private GetReviewDetailUseCase $useCase;
    private GetReviewDetailActionResponder $responder;

    public function __construct(
        GetReviewDetailUseCase $useCase,
        GetReviewDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetReviewDetailActionResource
    {
        $authUserId = auth()->id() ? (int) auth()->id() : null;
        $result = $this->useCase->__invoke($id, $authUserId);

        return $this->responder->__invoke($result);
    }
}
