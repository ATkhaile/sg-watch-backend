<?php

namespace App\Http\Actions\Api\Banner;

use App\Domain\Banner\UseCase\CreateBannerUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Banner\CreateBannerRequest;
use App\Http\Resources\Api\Banner\CreateBannerActionResource;
use App\Http\Responders\Api\Banner\CreateBannerActionResponder;

class CreateBannerAction extends BaseController
{
    private CreateBannerUseCase $useCase;
    private CreateBannerActionResponder $responder;

    public function __construct(
        CreateBannerUseCase $useCase,
        CreateBannerActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateBannerRequest $request): CreateBannerActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('media')) {
            $data['media'] = $request->file('media');
        }
        $result = $this->useCase->__invoke($data);
        return $this->responder->__invoke($result);
    }
}
