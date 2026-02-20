<?php

namespace App\Domain\Stripe\UseCase;

use App\Domain\Stripe\Repository\StripeRepository;
use App\Domain\Stripe\Entity\GetPortalLinkRequestEntity;
use App\Domain\Stripe\Entity\PortalLinkResponseEntity;

final class GetPortalLinkUseCase
{
    public function __construct(
        private StripeRepository $stripeRepository
    ) {
    }

    public function __invoke(GetPortalLinkRequestEntity $requestEntity): PortalLinkResponseEntity
    {
        return $this->stripeRepository->createPortalLink($requestEntity);
    }
}
