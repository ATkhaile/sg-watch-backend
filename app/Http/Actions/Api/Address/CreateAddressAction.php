<?php

namespace App\Http\Actions\Api\Address;

use App\Domain\Address\Factory\CreateAddressRequestFactory;
use App\Domain\Address\UseCase\CreateAddressUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Address\CreateAddressRequest;
use App\Http\Resources\Api\Address\CreateAddressActionResource;
use App\Http\Responders\Api\Address\CreateAddressActionResponder;

class CreateAddressAction extends BaseController
{
    private CreateAddressUseCase $createAddressUseCase;
    private CreateAddressRequestFactory $factory;
    private CreateAddressActionResponder $responder;

    public function __construct(
        CreateAddressUseCase $createAddressUseCase,
        CreateAddressRequestFactory $factory,
        CreateAddressActionResponder $responder
    ) {
        $this->createAddressUseCase = $createAddressUseCase;
        $this->factory = $factory;
        $this->responder = $responder;
    }

    public function __invoke(CreateAddressRequest $request): CreateAddressActionResource|\Illuminate\Http\JsonResponse
    {
        try {
            $entity = $this->factory->createFromRequest($request);
            $addresses = $this->createAddressUseCase->__invoke($entity);

            return $this->responder->__invoke($addresses);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 422,
                'data' => null,
            ], 422);
        }
    }
}
