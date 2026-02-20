<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\DeleteStripeAccountRequestEntity;
use App\Http\Requests\Api\StripeAccount\DeleteStripeAccountRequest;

class DeleteStripeAccountRequestFactory
{
    public function createFromRequest(DeleteStripeAccountRequest $request): DeleteStripeAccountRequestEntity
    {
        return new DeleteStripeAccountRequestEntity(
            id: (int) $request->route('id')
        );
    }
}
