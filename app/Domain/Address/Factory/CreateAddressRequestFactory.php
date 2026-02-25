<?php

namespace App\Domain\Address\Factory;

use App\Domain\Address\Entity\CreateAddressRequestEntity;
use App\Http\Requests\Api\Address\CreateAddressRequest;

class CreateAddressRequestFactory
{
    public function createFromRequest(CreateAddressRequest $request): CreateAddressRequestEntity
    {
        $countryCode = $request->input('country_code');
        $detail = [];

        if ($countryCode === 'JP') {
            $detail = $request->input('jp_detail', []);
        } elseif ($countryCode === 'VN') {
            $detail = $request->input('vn_detail', []);
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('addresses', 'public');
        }

        return new CreateAddressRequestEntity(
            label: $request->input('label'),
            countryCode: $countryCode,
            inputMode: $request->input('input_mode', 'manual'),
            postalCode: $request->input('postal_code'),
            phone: $request->input('phone'),
            imageUrl: $imageUrl,
            isDefault: (bool) $request->input('is_default', false),
            detail: $detail,
        );
    }
}
