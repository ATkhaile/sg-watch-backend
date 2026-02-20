<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Enums\StatusCode;
use App\Domain\StripeAccount\Entity\StatusEntity;
use App\Domain\StripeAccount\Entity\CreateStripeAccountRequestEntity;

final class CreateStripeAccountUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'create-stripe-account';

    private StripeAccountRepository $stripeAccountRepository;

    public function __construct(StripeAccountRepository $stripeAccountRepository)
    {
        $this->stripeAccountRepository = $stripeAccountRepository;
    }

    public function __invoke(CreateStripeAccountRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        if ($this->stripeAccountRepository->store($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('stripe_account.create.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('stripe_account.create.failed')
            );
        }
    }
}
