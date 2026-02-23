<?php

namespace App\Http\Actions\Api\ShopReview;

use App\Domain\ShopReview\UseCase\GetProductReviewsUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopReview\GetProductReviewsActionResource;
use App\Http\Responders\Api\ShopReview\GetProductReviewsActionResponder;
use Illuminate\Http\Request;

class GetProductReviewsAction extends BaseController
{
    private GetProductReviewsUseCase $useCase;
    private GetProductReviewsActionResponder $responder;

    public function __construct(
        GetProductReviewsUseCase $useCase,
        GetProductReviewsActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, int $productId): GetProductReviewsActionResource
    {
        $perPage = (int) $request->query('per_page', 15);
        $result = $this->useCase->__invoke($productId, $perPage);

        return $this->responder->__invoke($result);
    }
}
