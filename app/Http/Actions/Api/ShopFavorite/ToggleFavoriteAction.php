<?php

namespace App\Http\Actions\Api\ShopFavorite;

use App\Domain\ShopFavorite\UseCase\ToggleFavoriteUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\ShopFavorite\ToggleFavoriteRequest;
use App\Http\Resources\Api\ShopFavorite\ToggleFavoriteActionResource;
use App\Http\Responders\Api\ShopFavorite\ToggleFavoriteActionResponder;

class ToggleFavoriteAction extends BaseController
{
    private ToggleFavoriteUseCase $useCase;
    private ToggleFavoriteActionResponder $responder;

    public function __construct(
        ToggleFavoriteUseCase $useCase,
        ToggleFavoriteActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(ToggleFavoriteRequest $request): ToggleFavoriteActionResource
    {
        $userId = (int) auth()->user()->id;

        $result = $this->useCase->__invoke(
            $userId,
            (int) $request->validated('product_id'),
        );

        return $this->responder->__invoke($result);
    }
}
