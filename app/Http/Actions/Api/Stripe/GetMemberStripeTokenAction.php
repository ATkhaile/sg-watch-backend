<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\GetMemberStripeTokenUseCase;
use App\Domain\Stripe\Factory\GetMemberStripeTokenRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Stripe\GetMemberStripeTokenResource;
use App\Http\Responders\Api\Stripe\GetMemberStripeTokenResponder;
use Illuminate\Http\Request;

class GetMemberStripeTokenAction extends BaseController
{
    public function __construct(
        private GetMemberStripeTokenUseCase $getStripeTokenUseCase,
        private GetMemberStripeTokenRequestFactory $factory,
        private GetMemberStripeTokenResponder $responder
    ) {
    }

    public function __invoke(Request $request): GetMemberStripeTokenResource
    {
        $locationId = $request->query('location_id') ? (int) $request->query('location_id') : null;
        $token = $this->getStripeTokenUseCase->__invoke($locationId);
        return $this->responder->__invoke($token);
    }
}
