<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\TestStripeConnectionRequestEntity;
use App\Http\Requests\Api\StripeAccount\TestStripeConnectionRequest;

class TestStripeConnectionRequestFactory
{
    public function createFromRequest(TestStripeConnectionRequest $request): TestStripeConnectionRequestEntity
    {
        return new TestStripeConnectionRequestEntity(
            publicKey: $request->input('public_key'),
            secretKey: $request->input('secret_key'),
            webhookSecret: $request->input('webhook_secret'),
        );
    }
}
