<?php

namespace App\Http\Actions\Api\Banner;

use App\Domain\Banner\UseCase\GetBannerDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Banner\GetBannerDetailActionResource;
use App\Http\Responders\Api\Banner\GetBannerDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetBannerDetailAction extends BaseController
{
    private GetBannerDetailUseCase $useCase;
    private GetBannerDetailActionResponder $responder;

    public function __construct(
        GetBannerDetailUseCase $useCase,
        GetBannerDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetBannerDetailActionResource|JsonResponse
    {
        $banner = $this->useCase->__invoke($id);
        if (!$banner) {
            return response()->json([
                'message' => 'Banner not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }
        return $this->responder->__invoke($banner);
    }
}
