<?php

namespace App\Domain\Address\Factory;

use App\Domain\Address\Entity\UpdateAddressRequestEntity;
use App\Http\Requests\Api\Address\UpdateAddressRequest;

class UpdateAddressRequestFactory
{
    public function createFromRequest(UpdateAddressRequest $request, int $addressId): UpdateAddressRequestEntity
    {
        $countryCode = $request->input('country_code');
        $detail = [];

        if ($countryCode === 'JP') {
            $detail = $request->input('jp_detail', []);
        } elseif ($countryCode === 'VN') {
            $detail = $request->input('vn_detail', []);
        }

        return new UpdateAddressRequestEntity(
            addressId: $addressId,
            label: $request->input('label'),
            inputMode: $request->input('input_mode'),
            postalCode: $request->input('postal_code'),
            phone: $request->input('phone'),
            imageUrl: $request->input('image_url'),
            isDefault: $request->has('is_default') ? (bool) $request->input('is_default') : null,
            detail: $detail,
        );
    }
}
