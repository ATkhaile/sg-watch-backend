<?php

namespace App\Domain\Notifications\Entity;

class GetNotificationsDetailRequestEntity
{
    public function __construct(
        public readonly int $id
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
