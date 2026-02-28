<?php

namespace App\Http\Actions\Api\Notice;

use App\Domain\Notice\UseCase\UpdateNoticeUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Notice\UpdateNoticeRequest;
use App\Http\Resources\Api\Notice\UpdateNoticeActionResource;
use App\Http\Responders\Api\Notice\UpdateNoticeActionResponder;

class UpdateNoticeAction extends BaseController
{
    private UpdateNoticeUseCase $useCase;
    private UpdateNoticeActionResponder $responder;

    public function __construct(
        UpdateNoticeUseCase $useCase,
        UpdateNoticeActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(UpdateNoticeRequest $request, int $id): UpdateNoticeActionResource
    {
        $result = $this->useCase->__invoke($id, $request->validated());
        return $this->responder->__invoke($result);
    }
}
