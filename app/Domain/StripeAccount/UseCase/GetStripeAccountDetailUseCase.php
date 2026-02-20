<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\StripeAccount\Entity\StripeAccountDetailEntity;
use App\Domain\StripeAccount\Entity\GetStripeAccountDetailRequestEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Exceptions\Domain\NotFoundResourceException;

final class GetStripeAccountDetailUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'find-stripe-account';

    public function __construct(
        private StripeAccountRepository $stripeAccountRepository
    ) {}

    public function __invoke(GetStripeAccountDetailRequestEntity $requestEntity): StripeAccountDetailEntity
    {
        $this->authorize();

        $stripeAccountsId = $requestEntity->getId();
        $stripeAccounts = $this->stripeAccountRepository->getStripeAccountDetail($stripeAccountsId);

        if (!$stripeAccounts) {
            throw new NotFoundResourceException(message: __('messages.error'));
        }

        return $stripeAccounts;
    }
}
