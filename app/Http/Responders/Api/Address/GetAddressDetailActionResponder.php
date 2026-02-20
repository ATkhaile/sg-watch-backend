<?php

namespace App\Http\Responders\Api\Address;

use App\Http\Resources\Api\Address\GetAddressDetailActionResource;

final class GetAddressDetailActionResponder
{
    public function __invoke(array $address): GetAddressDetailActionResource
    {
        return new GetAddressDetailActionResource([
            'address' => $address,
        ]);
    }
}
