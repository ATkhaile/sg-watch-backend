<?php

namespace App\Http\Actions\Api\Notice;

use App\Domain\Notice\UseCase\GetNoticeDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Notice\GetNoticeDetailActionResource;
use App\Http\Responders\Api\Notice\GetNoticeDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetNoticeDetailAction extends BaseController
{
    private GetNoticeDetailUseCase $useCase;
    private GetNoticeDetailActionResponder $responder;

    public function __construct(
        GetNoticeDetailUseCase $useCase,
        GetNoticeDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetNoticeDetailActionResource|JsonResponse
    {
        $notice = $this->useCase->__invoke($id);
        if (!$notice) {
            return response()->json([
                'message' => 'Notice not found.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }
        return $this->responder->__invoke($notice);
    }
}
