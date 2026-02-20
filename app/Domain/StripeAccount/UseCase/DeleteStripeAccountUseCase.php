<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\StripeAccount\Entity\StatusEntity;
use App\Domain\StripeAccount\Entity\DeleteStripeAccountRequestEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Enums\StatusCode;

final class DeleteStripeAccountUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'delete-stripe-account';

    public function __construct(
        private StripeAccountRepository $stripeAccountRepository
    ) {}

    public function __invoke(DeleteStripeAccountRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        if ($this->stripeAccountRepository->delete($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('stripe_account.delete.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('stripe_account.delete.failed')
            );
        }
    }
}
