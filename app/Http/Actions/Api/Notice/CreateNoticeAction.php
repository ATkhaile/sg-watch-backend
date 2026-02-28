<?php

namespace App\Http\Actions\Api\Notice;

use App\Domain\Notice\UseCase\CreateNoticeUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Notice\CreateNoticeRequest;
use App\Http\Resources\Api\Notice\CreateNoticeActionResource;
use App\Http\Responders\Api\Notice\CreateNoticeActionResponder;

class CreateNoticeAction extends BaseController
{
    private CreateNoticeUseCase $useCase;
    private CreateNoticeActionResponder $responder;

    public function __construct(
        CreateNoticeUseCase $useCase,
        CreateNoticeActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(CreateNoticeRequest $request): CreateNoticeActionResource
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $result = $this->useCase->__invoke($data);
        return $this->responder->__invoke($result);
    }
}
