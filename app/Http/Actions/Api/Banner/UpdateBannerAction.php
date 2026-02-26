<?php

namespace App\Http\Actions\Api\Banner;

use App\Domain\Banner\UseCase\UpdateBannerUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Banner\UpdateBannerRequest;
use App\Http\Resources\Api\Banner\UpdateBannerActionResource;
use App\Http\Responders\Api\Banner\UpdateBannerActionResponder;

class UpdateBannerAction extends BaseController
{
    private UpdateBannerUseCase $useCase;
    private UpdateBannerActionResponder $responder;

    public function __construct(
        UpdateBannerUseCase $useCase,
        UpdateBannerActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateBannerRequest $request, int $id): UpdateBannerActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $result = $this->useCase->__invoke($id, $data);
        return $this->responder->__invoke($result);
    }
}
