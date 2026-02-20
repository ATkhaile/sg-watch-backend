<?php

namespace App\Domain\Stripe\UseCase;

use App\Domain\Stripe\Repository\StripeRepository;
use App\Domain\Stripe\Entity\SubmitCancelRequestEntity;
use App\Domain\Stripe\Entity\CancelResponseEntity;

final class SubmitCancelUseCase
{
    public function __construct(
        private StripeRepository $stripeRepository
    ) {
    }

    public function __invoke(SubmitCancelRequestEntity $requestEntity): CancelResponseEntity
    {
        return $this->stripeRepository->submitCancel($requestEntity);
    }
}
