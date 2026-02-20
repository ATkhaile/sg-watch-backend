<?php

namespace App\Http\Responders\Api\Address;

use App\Http\Resources\Api\Address\UpdateAddressActionResource;

final class UpdateAddressActionResponder
{
    public function __invoke(array $address): UpdateAddressActionResource
    {
        return new UpdateAddressActionResource([
            'address' => $address,
        ]);
    }
}
