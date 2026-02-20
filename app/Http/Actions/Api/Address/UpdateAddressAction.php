<?php

namespace App\Http\Actions\Api\Address;

use App\Domain\Address\Factory\UpdateAddressRequestFactory;
use App\Domain\Address\UseCase\UpdateAddressUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Address\UpdateAddressRequest;
use App\Http\Resources\Api\Address\UpdateAddressActionResource;
use App\Http\Responders\Api\Address\UpdateAddressActionResponder;

class UpdateAddressAction extends BaseController
{
    private UpdateAddressUseCase $updateAddressUseCase;
    private UpdateAddressRequestFactory $factory;
    private UpdateAddressActionResponder $responder;

    public function __construct(
        UpdateAddressUseCase $updateAddressUseCase,
        UpdateAddressRequestFactory $factory,
        UpdateAddressActionResponder $responder
    ) {
        $this->updateAddressUseCase = $updateAddressUseCase;
        $this->factory = $factory;
        $this->responder = $responder;
    }

    public function __invoke(UpdateAddressRequest $request, int $id): UpdateAddressActionResource
    {
        $entity = $this->factory->createFromRequest($request, $id);
        $addresses = $this->updateAddressUseCase->__invoke($entity);

        return $this->responder->__invoke($addresses);
    }
}
