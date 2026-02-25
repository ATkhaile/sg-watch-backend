<?php

namespace App\Http\Actions\Api\AdminUser;

use App\Domain\AdminUser\UseCase\DeleteAdminUserUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\AdminUser\DeleteAdminUserActionResource;
use App\Http\Responders\Api\AdminUser\DeleteAdminUserActionResponder;

class DeleteAdminUserAction extends BaseController
{
    private DeleteAdminUserUseCase $useCase;
    private DeleteAdminUserActionResponder $responder;

    public function __construct(
        DeleteAdminUserUseCase $useCase,
        DeleteAdminUserActionResponder $responder
    ) {
        $this->useCase = $useCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteAdminUserActionResource
    {
        $currentUserId = (int) auth()->id();
        $result = $this->useCase->__invoke($id, $currentUserId);

        return $this->responder->__invoke($result);
    }
}
