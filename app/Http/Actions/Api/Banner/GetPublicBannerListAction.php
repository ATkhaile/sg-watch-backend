<?php

namespace App\Http\Actions\Api\Banner;

use App\Domain\Banner\UseCase\GetPublicBannerListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Banner\GetPublicBannerListActionResource;
use App\Http\Responders\Api\Banner\GetPublicBannerListActionResponder;

class GetPublicBannerListAction extends BaseController
{
    private GetPublicBannerListUseCase $useCase;
    private GetPublicBannerListActionResponder $responder;

    public function __construct(
        GetPublicBannerListUseCase $useCase,
        GetPublicBannerListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(): GetPublicBannerListActionResource
    {
        $result = $this->useCase->__invoke();
        return $this->responder->__invoke($result);
    }
}
