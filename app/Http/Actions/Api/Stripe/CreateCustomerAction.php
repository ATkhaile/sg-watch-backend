<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\CreateCustomerUseCase;
use App\Domain\Stripe\Factory\CreateCustomerRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Stripe\ActionResource;
use App\Http\Responders\Api\Stripe\ActionResponder;
use Illuminate\Http\Request;

class CreateCustomerAction extends BaseController
{
    public function __construct(
        private CreateCustomerUseCase $createCustomerUseCase,
        private CreateCustomerRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(Request $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->createCustomerUseCase->__invoke($requestEntity);

        return $this->responder->__invoke($statusEntity);
    }
}
