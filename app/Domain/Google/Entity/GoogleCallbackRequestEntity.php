<?php

namespace App\Domain\Google\Entity;

class GoogleCallbackRequestEntity
{
    public function __construct(
        public readonly string $code,
        public readonly ?string $type = null,
        public readonly ?string $redirect_url = null
    ) {}

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'type' => $this->type,
            'redirect_url' => $this->redirect_url,
        ];
    }
}
