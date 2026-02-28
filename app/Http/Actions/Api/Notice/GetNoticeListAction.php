<?php

namespace App\Http\Actions\Api\Notice;

use App\Domain\Notice\UseCase\GetNoticeListUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Notice\GetNoticeListRequest;
use App\Http\Resources\Api\Notice\GetNoticeListActionResource;
use App\Http\Responders\Api\Notice\GetNoticeListActionResponder;

class GetNoticeListAction extends BaseController
{
    private GetNoticeListUseCase $useCase;
    private GetNoticeListActionResponder $responder;

    public function __construct(
        GetNoticeListUseCase $useCase,
        GetNoticeListActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetNoticeListRequest $request): GetNoticeListActionResource
    {
        $result = $this->useCase->__invoke($request->validated());
        return $this->responder->__invoke($result);
    }
}
