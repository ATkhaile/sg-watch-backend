<?php

namespace App\Http\Actions\Api\ShopReview;

use App\Domain\ShopReview\UseCase\GetMyReviewsUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopReview\GetMyReviewsActionResource;
use App\Http\Responders\Api\ShopReview\GetMyReviewsActionResponder;
use Illuminate\Http\Request;

class GetMyReviewsAction extends BaseController
{
    private GetMyReviewsUseCase $useCase;
    private GetMyReviewsActionResponder $responder;

    public function __construct(
        GetMyReviewsUseCase $useCase,
        GetMyReviewsActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): GetMyReviewsActionResource
    {
        $userId = (int) auth()->user()->id;
        $perPage = (int) $request->query('per_page', 15);
        $result = $this->useCase->__invoke($userId, $perPage);

        return $this->responder->__invoke($result);
    }
}
