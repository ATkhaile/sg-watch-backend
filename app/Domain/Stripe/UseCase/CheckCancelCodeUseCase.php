<?php

namespace App\Domain\Stripe\UseCase;

use App\Domain\Stripe\Repository\StripeRepository;
use App\Domain\Stripe\Entity\CheckCancelCodeRequestEntity;
use App\Domain\Stripe\Entity\StatusEntity;

final class CheckCancelCodeUseCase
{
    public function __construct(
        private StripeRepository $stripeRepository
    ) {
    }

    public function __invoke(CheckCancelCodeRequestEntity $requestEntity): StatusEntity
    {
        return $this->stripeRepository->checkCancelCode($requestEntity);
    }
}
