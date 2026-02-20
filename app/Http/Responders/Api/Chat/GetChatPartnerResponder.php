<?php

namespace App\Http\Responders\Api\Chat;

use App\Domain\Chat\Entity\ChatPartnerEntity;
use App\Http\Resources\Api\Chat\GetChatPartnerResource;

final class GetChatPartnerResponder
{
    public function __invoke(ChatPartnerEntity $entity): GetChatPartnerResource
    {
        $resource = $this->makeResource($entity);
        return new GetChatPartnerResource($resource);
    }

    public function makeResource(ChatPartnerEntity $entity): array
    {
        return [
            'status_code' => $entity->getStatus(),
            'data' => [
                'partner' => $entity->getPartner(),
            ],
        ];
    }
}
