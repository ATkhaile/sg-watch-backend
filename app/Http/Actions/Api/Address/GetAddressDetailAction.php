<?php

namespace App\Http\Actions\Api\Address;

use App\Domain\Address\UseCase\GetAddressDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Address\GetAddressDetailActionResource;
use App\Http\Responders\Api\Address\GetAddressDetailActionResponder;

class GetAddressDetailAction extends BaseController
{
    private GetAddressDetailUseCase $getAddressDetailUseCase;
    private GetAddressDetailActionResponder $responder;

    public function __construct(
        GetAddressDetailUseCase $getAddressDetailUseCase,
        GetAddressDetailActionResponder $responder
    ) {
        $this->getAddressDetailUseCase = $getAddressDetailUseCase;
        $this->responder = $responder;
    }

    public function __invoke(int $id): GetAddressDetailActionResource
    {
        $address = $this->getAddressDetailUseCase->__invoke($id);

        return $this->responder->__invoke($address);
    }
}
