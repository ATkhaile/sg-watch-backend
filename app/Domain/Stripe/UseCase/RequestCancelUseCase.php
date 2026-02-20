<?php

namespace App\Domain\Stripe\UseCase;

use App\Domain\Stripe\Repository\StripeRepository;
use App\Domain\Stripe\Entity\RequestCancelRequestEntity;
use App\Domain\Stripe\Entity\StatusEntity;

final class RequestCancelUseCase
{
    public function __construct(
        private StripeRepository $stripeRepository
    ) {
    }

    public function __invoke(RequestCancelRequestEntity $requestEntity): StatusEntity
    {
        return $this->stripeRepository->requestCancel($requestEntity);
    }
}
