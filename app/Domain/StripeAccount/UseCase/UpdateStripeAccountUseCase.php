<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\StripeAccount\Entity\StatusEntity;
use App\Domain\StripeAccount\Entity\UpdateStripeAccountRequestEntity;
use App\Domain\StripeAccount\Repository\StripeAccountRepository;
use App\Enums\StatusCode;

final class UpdateStripeAccountUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-stripe-account';

    public function __construct(
        private StripeAccountRepository $stripeAccountRepository
    ) {}

    public function __invoke(UpdateStripeAccountRequestEntity $requestEntity, int $id): StatusEntity
    {
        $this->authorize();

        $success = $this->stripeAccountRepository->update($requestEntity, $id);
        if (!$success) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('stripe_accounts.update.failed')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('stripe_account.update.success')
            );
        }
    }
}
