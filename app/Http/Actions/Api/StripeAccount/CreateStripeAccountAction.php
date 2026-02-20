<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\CreateStripeAccountUseCase;
use App\Domain\StripeAccount\Factory\CreateStripeAccountRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\CreateStripeAccountRequest;
use App\Http\Resources\Api\StripeAccount\ActionResource;
use App\Http\Responders\Api\StripeAccount\ActionResponder;

class CreateStripeAccountAction extends BaseController
{
    public function __construct(
        private CreateStripeAccountUseCase $createStripeAccountUseCase,
        private CreateStripeAccountRequestFactory $factory,
        private ActionResponder $responder
    ) {}


    public function __invoke(CreateStripeAccountRequest $request): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);

        $statusEntity = $this->createStripeAccountUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
