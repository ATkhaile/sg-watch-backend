<?php

namespace App\Http\Actions\Api\Banner;

use App\Domain\Banner\UseCase\GetBannerListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Banner\GetBannerListRequest;
use App\Http\Resources\Api\Banner\GetBannerListActionResource;
use App\Http\Responders\Api\Banner\GetBannerListActionResponder;

class GetBannerListAction extends BaseController
{
    private GetBannerListUseCase $useCase;
    private GetBannerListActionResponder $responder;

    public function __construct(
        GetBannerListUseCase $useCase,
        GetBannerListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetBannerListRequest $request): GetBannerListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
