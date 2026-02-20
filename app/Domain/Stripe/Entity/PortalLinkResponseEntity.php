<?php

namespace App\Domain\Stripe\Entity;

use App\Enums\StatusCode;

class PortalLinkResponseEntity
{
    public function __construct(
        public readonly ?string $portalLink,
        public readonly StatusCode $statusCode,
        public readonly string $message = ''
    ) {
    }

    public function toArray(): array
    {
        return [
            'portal_link' => $this->portalLink,
            'status_code' => $this->statusCode->value,
            'message' => $this->message
        ];
    }
}
