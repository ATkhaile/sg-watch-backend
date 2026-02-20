<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\UpdateStripeAccountUseCase;
use App\Domain\StripeAccount\Factory\UpdateStripeAccountRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\UpdateStripeAccountRequest;
use App\Http\Resources\Api\StripeAccount\ActionResource;
use App\Http\Responders\Api\StripeAccount\ActionResponder;

class UpdateStripeAccountDetailAction extends BaseController
{
    public function __construct(
        private UpdateStripeAccountUseCase $updateStripeAccountUseCase,
        private UpdateStripeAccountRequestFactory $factory,
        private ActionResponder $responder
    ) {}

    public function __invoke(UpdateStripeAccountRequest $request, int $id): ActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->updateStripeAccountUseCase->__invoke($requestEntity, $id);
        return $this->responder->__invoke($statusEntity);
    }
}
