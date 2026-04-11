<?php

namespace App\Http\Actions\Api\Notice;

use App\Domain\Notice\UseCase\GetMemberNoticeDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Notice\GetMemberNoticeDetailActionResource;
use App\Http\Responders\Api\Notice\GetMemberNoticeDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetMemberNoticeDetailAction extends BaseController
{
    private GetMemberNoticeDetailUseCase $useCase;
    private GetMemberNoticeDetailActionResponder $responder;

    public function __construct(
        GetMemberNoticeDetailUseCase $useCase,
        GetMemberNoticeDetailActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(string $id): GetMemberNoticeDetailActionResource|JsonResponse
    {
        $userId = (int) auth()->user()->id;
        $notice = $this->useCase->__invoke($userId, $id);

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
