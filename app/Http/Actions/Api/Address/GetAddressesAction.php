<?php

namespace App\Http\Actions\Api\Address;

use App\Domain\Address\UseCase\GetAddressesUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Address\GetAddressesActionResource;
use App\Http\Responders\Api\Address\GetAddressesActionResponder;

class GetAddressesAction extends BaseController
{
    private GetAddressesUseCase $getAddressesUseCase;
    private GetAddressesActionResponder $responder;

    public function __construct(
        GetAddressesUseCase $getAddressesUseCase,
        GetAddressesActionResponder $responder
    ) {
        $this->getAddressesUseCase = $getAddressesUseCase;
        $this->responder = $responder;
    }

    public function __invoke(): GetAddressesActionResource
    {
        $addresses = $this->getAddressesUseCase->__invoke();

        return $this->responder->__invoke($addresses);
    }
}
