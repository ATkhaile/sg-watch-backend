<?php

namespace App\Http\Responders\Api\Address;

use App\Http\Resources\Api\Address\GetAddressesActionResource;

final class GetAddressesActionResponder
{
    public function __invoke(array $addresses): GetAddressesActionResource
    {
        return new GetAddressesActionResource([
            'addresses' => $addresses,
        ]);
    }
}
