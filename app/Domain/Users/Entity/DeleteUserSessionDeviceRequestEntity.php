<?php

namespace App\Domain\Users\Entity;

class DeleteUserSessionDeviceRequestEntity
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
