<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\StripeAccount\Entity\GetStripePaymentLinksRequestEntity;
use App\Domain\StripeAccount\Entity\StripePaymentLinkEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Domain\Shared\Concerns\RequiresPermission;

class GetStripePaymentLinksUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'view-stripe-products';
    private StripeAccountRepository $stripeAccountRepository;

    public function __construct(StripeAccountRepository $stripeAccountRepository)
    {
        $this->stripeAccountRepository = $stripeAccountRepository;
    }

    public function __invoke(GetStripePaymentLinksRequestEntity $requestEntity): StripePaymentLinkEntity
    {
        $this->authorize();

        return $this->stripeAccountRepository->getStripePaymentLinks($requestEntity);
    }
}
