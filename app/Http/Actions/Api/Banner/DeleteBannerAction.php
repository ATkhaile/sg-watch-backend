<?php

namespace App\Http\Actions\Api\Banner;

use App\Domain\Banner\UseCase\DeleteBannerUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Banner\DeleteBannerActionResource;
use App\Http\Responders\Api\Banner\DeleteBannerActionResponder;

class DeleteBannerAction extends BaseController
{
    private DeleteBannerUseCase $useCase;
    private DeleteBannerActionResponder $responder;

    public function __construct(
        DeleteBannerUseCase $useCase,
        DeleteBannerActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteBannerActionResource
    {
        $result = $this->useCase->__invoke($id);
        return $this->responder->__invoke($result);
    }
}
