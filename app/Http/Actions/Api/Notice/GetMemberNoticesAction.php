<?php

namespace App\Http\Actions\Api\Notice;

use App\Domain\Notice\UseCase\GetMemberNoticesUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Notice\GetMemberNoticesRequest;
use App\Http\Resources\Api\Notice\GetMemberNoticesActionResource;
use App\Http\Responders\Api\Notice\GetMemberNoticesActionResponder;

class GetMemberNoticesAction extends BaseController
{
    private GetMemberNoticesUseCase $useCase;
    private GetMemberNoticesActionResponder $responder;

    public function __construct(
        GetMemberNoticesUseCase $useCase,
        GetMemberNoticesActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(GetMemberNoticesRequest $request): GetMemberNoticesActionResource
    {
        $userId = (int) auth()->user()->id;
        $result = $this->useCase->__invoke($userId, $request->validated());
        return $this->responder->__invoke($result);
    }
}
