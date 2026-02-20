<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\GetCustomerSubscriptionsUseCase;
use App\Domain\StripeAccount\Factory\GetCustomerSubscriptionsRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\GetCustomerSubscriptionsRequest;
use App\Http\Resources\Api\StripeAccount\CustomerSubscriptionsResource;
use App\Http\Responders\Api\StripeAccount\GetCustomerSubscriptionsResponder;
use Illuminate\Support\Facades\Auth;

class GetCustomerSubscriptionsAction extends BaseController
{
    public function __construct(
        private GetCustomerSubscriptionsUseCase $getCustomerSubscriptionsUseCase,
        private GetCustomerSubscriptionsRequestFactory $factory,
        private GetCustomerSubscriptionsResponder $responder
    ) {}

    public function __invoke(GetCustomerSubscriptionsRequest $request): CustomerSubscriptionsResource
    {
        $admin = Auth::guard('api')->user();
        $requestEntity = $this->factory->createFromRequest($request);
        $result = $this->getCustomerSubscriptionsUseCase->__invoke($requestEntity, $admin->id);
        return $this->responder->__invoke($result);
    }
}
