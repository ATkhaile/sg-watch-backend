<?php

namespace App\Http\Responders\Api\Address;

use App\Http\Resources\Api\Address\CreateAddressActionResource;

final class CreateAddressActionResponder
{
    public function __invoke(array $address): CreateAddressActionResource
    {
        return new CreateAddressActionResource([
            'address' => $address,
        ]);
    }
}
