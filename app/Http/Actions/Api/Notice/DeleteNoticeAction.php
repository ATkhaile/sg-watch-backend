<?php

namespace App\Http\Actions\Api\Notice;

use App\Domain\Notice\UseCase\DeleteNoticeUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Notice\DeleteNoticeActionResource;
use App\Http\Responders\Api\Notice\DeleteNoticeActionResponder;

class DeleteNoticeAction extends BaseController
{
    private DeleteNoticeUseCase $useCase;
    private DeleteNoticeActionResponder $responder;

    public function __construct(
        DeleteNoticeUseCase $useCase,
        DeleteNoticeActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteNoticeActionResource
    {
        $result = $this->useCase->__invoke($id);
        return $this->responder->__invoke($result);
    }
}
