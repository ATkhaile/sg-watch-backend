<?php

namespace App\Http\Actions\Api\Address;

use App\Domain\Address\UseCase\DeleteAddressUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Address\DeleteAddressActionResource;
use App\Http\Responders\Api\Address\DeleteAddressActionResponder;

class DeleteAddressAction extends BaseController
{
    private DeleteAddressUseCase $deleteAddressUseCase;
    private DeleteAddressActionResponder $responder;

    public function __construct(
        DeleteAddressUseCase $deleteAddressUseCase,
        DeleteAddressActionResponder $responder
    ) {
        $this->deleteAddressUseCase = $deleteAddressUseCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): DeleteAddressActionResource
    {
        $this->deleteAddressUseCase->__invoke($id);

        return $this->responder->__invoke();
    }
}
