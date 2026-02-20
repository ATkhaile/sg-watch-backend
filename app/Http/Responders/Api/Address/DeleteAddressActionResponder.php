<?php

namespace App\Http\Responders\Api\Address;

use App\Http\Resources\Api\Address\DeleteAddressActionResource;

final class DeleteAddressActionResponder
{
    public function __invoke(): DeleteAddressActionResource
    {
        return new DeleteAddressActionResource([]);
    }
}
