<?php

namespace App\Domain\Stripe\UseCase;

use App\Domain\Stripe\Repository\StripeRepository;
use App\Domain\Stripe\Entity\CreateCustomerRequestEntity;
use App\Domain\Stripe\Entity\StatusEntity;

final class CreateCustomerUseCase
{
    public function __construct(
        private StripeRepository $stripeRepository
    ) {
    }

    public function __invoke(CreateCustomerRequestEntity $requestEntity): StatusEntity
    {
        return $this->stripeRepository->handleWebhook($requestEntity);
    }
}
