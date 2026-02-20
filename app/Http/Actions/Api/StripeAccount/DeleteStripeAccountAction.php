<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\DeleteStripeAccountUseCase;
use App\Domain\StripeAccount\Factory\DeleteStripeAccountRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\StripeAccount\ActionResource;
use App\Http\Responders\Api\StripeAccount\ActionResponder;
use App\Http\Requests\Api\StripeAccount\DeleteStripeAccountRequest;

class DeleteStripeAccountAction extends BaseController
{
    public function __construct(
        private DeleteStripeAccountUseCase $useCase,
        private DeleteStripeAccountRequestFactory $factory,
        private ActionResponder $responder
    ) {}

    public function __invoke(DeleteStripeAccountRequest $request, int $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($statusEntity);
    }
}
