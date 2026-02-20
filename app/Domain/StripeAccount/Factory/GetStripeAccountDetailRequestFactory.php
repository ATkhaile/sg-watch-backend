<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\GetStripeAccountDetailRequestEntity;
use App\Http\Requests\Api\StripeAccount\FindStripeAccountRequest;

class GetStripeAccountDetailRequestFactory
{
    public function createFromRequest(FindStripeAccountRequest $request): GetStripeAccountDetailRequestEntity
    {
        $entity = new GetStripeAccountDetailRequestEntity;
        $entity->setId((int) $request->route('id'));
        return $entity;
    }
}
