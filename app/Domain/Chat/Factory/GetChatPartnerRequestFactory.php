<?php

namespace App\Domain\Chat\Factory;

use App\Domain\Chat\Entity\ChatPartnerEntity;
use App\Http\Requests\Api\Chat\GetChatPartnerRequest;

class GetChatPartnerRequestFactory
{
    public function create(GetChatPartnerRequest $request, $user): ChatPartnerEntity
    {
        return new ChatPartnerEntity(
            userId: $user->id,
            partnerId: $request->getPartnerId()
        );
    }
}
