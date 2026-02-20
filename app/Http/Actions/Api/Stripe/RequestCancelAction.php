<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\RequestCancelUseCase;
use App\Domain\Stripe\Factory\RequestCancelRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Stripe\RequestCancelResource;
use App\Http\Responders\Api\Stripe\RequestCancelResponder;
use Illuminate\Http\Request;

class RequestCancelAction extends BaseController
{
    public function __construct(
        private RequestCancelUseCase $requestCancelUseCase,
        private RequestCancelRequestFactory $factory,
        private RequestCancelResponder $responder
    ) {
    }

    public function __invoke(Request $request): RequestCancelResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->requestCancelUseCase->__invoke($requestEntity);

        return $this->responder->__invoke($statusEntity);
    }
}
