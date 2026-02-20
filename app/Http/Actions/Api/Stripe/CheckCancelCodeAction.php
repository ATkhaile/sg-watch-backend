<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\CheckCancelCodeUseCase;
use App\Domain\Stripe\Factory\CheckCancelCodeRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Stripe\CheckCancelResource;
use App\Http\Responders\Api\Stripe\CheckCancelResponder;
use Illuminate\Http\Request;

class CheckCancelCodeAction extends BaseController
{
    public function __construct(
        private CheckCancelCodeUseCase $checkCancelCodeUseCase,
        private CheckCancelCodeRequestFactory $factory,
        private CheckCancelResponder $responder
    ) {
    }

    public function __invoke(Request $request): CheckCancelResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $statusEntity = $this->checkCancelCodeUseCase->__invoke($requestEntity);

        return $this->responder->__invoke($statusEntity);
    }
}
