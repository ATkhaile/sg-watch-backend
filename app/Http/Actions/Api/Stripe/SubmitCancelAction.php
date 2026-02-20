<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\SubmitCancelUseCase;
use App\Domain\Stripe\Factory\SubmitCancelRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Stripe\CancelResource;
use App\Http\Responders\Api\Stripe\CancelResponder;
use Illuminate\Http\Request;

class SubmitCancelAction extends BaseController
{
    public function __construct(
        private SubmitCancelUseCase $submitCancelUseCase,
        private SubmitCancelRequestFactory $factory,
        private CancelResponder $responder
    ) {
    }

    public function __invoke(Request $request): CancelResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $responseEntity = $this->submitCancelUseCase->__invoke($requestEntity);

        return $this->responder->__invoke($responseEntity);
    }
}
