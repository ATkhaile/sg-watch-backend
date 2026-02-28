<?php

namespace App\Http\Actions\Api\Notice;

use App\Domain\Notice\UseCase\MarkNoticeAsReadUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Notice\MarkNoticeAsReadActionResource;
use App\Http\Responders\Api\Notice\MarkNoticeAsReadActionResponder;

class MarkNoticeAsReadAction extends BaseController
{
    private MarkNoticeAsReadUseCase $useCase;
    private MarkNoticeAsReadActionResponder $responder;

    public function __construct(
        MarkNoticeAsReadUseCase $useCase,
        MarkNoticeAsReadActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): MarkNoticeAsReadActionResource
    {
        $userId = (int) auth()->user()->id;
        $result = $this->useCase->__invoke($userId, $id);
        return $this->responder->__invoke($result);
    }
}
