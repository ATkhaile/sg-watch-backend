<?php

namespace App\Http\Actions\Api\ShopFavorite;

use App\Domain\ShopFavorite\UseCase\GetFavoriteListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\ShopFavorite\GetFavoriteListActionResource;
use App\Http\Responders\Api\ShopFavorite\GetFavoriteListActionResponder;
use Illuminate\Http\Request;

class GetFavoriteListAction extends BaseController
{
    private GetFavoriteListUseCase $useCase;
    private GetFavoriteListActionResponder $responder;

    public function __construct(
        GetFavoriteListUseCase $useCase,
        GetFavoriteListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): GetFavoriteListActionResource
    {
        $userId = (int) auth()->user()->id;

        $favorites = $this->useCase->__invoke($userId);

        return $this->responder->__invoke($favorites);
    }
}
