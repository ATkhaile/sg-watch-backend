<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\GetAllStripeAccountFromCustomerUseCase;
use App\Domain\StripeAccount\Factory\GetAllStripeAccountFromCustomerRequestFactory;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\GetAllStripeAccountFromCustomerRequest;
use App\Http\Resources\Api\StripeAccount\GetAllStripeAccountActionResource;
use App\Http\Responders\Api\StripeAccount\GetAllStripeAccountActionResponder;
use Illuminate\Support\Facades\Auth;

class GetAllStripeAccountFromCustomerAction extends BaseController
{
    public function __construct(
        private GetAllStripeAccountFromCustomerUseCase $getAllStripeAccountFromCustomerUseCase,
        private GetAllStripeAccountFromCustomerRequestFactory $factory,
        private GetAllStripeAccountActionResponder $responder,
        private StripeAccountRepository $stripeAccountRepository
    ) {}

    public function __invoke(GetAllStripeAccountFromCustomerRequest $request): GetAllStripeAccountActionResource
    {
        $user = Auth::guard('api')->user();

        $activeStripeAccountId = $this->stripeAccountRepository->getUserActiveStripeAccountId($user->id);

        $requestEntity = $this->factory->createFromRequest($request, $activeStripeAccountId);
        $stripeAccounts = $this->getAllStripeAccountFromCustomerUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($stripeAccounts);
    }
}
