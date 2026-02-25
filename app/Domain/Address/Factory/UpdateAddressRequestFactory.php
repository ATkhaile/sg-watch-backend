<?php

namespace App\Domain\Address\Factory;

use App\Domain\Address\Entity\UpdateAddressRequestEntity;
use App\Http\Requests\Api\Address\UpdateAddressRequest;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Storage;

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

        $imageUrl = null;
        if ($request->hasFile('image')) {
            // Delete old image
            $address = UserAddress::find($addressId);
            if ($address && $address->image_url && Storage::disk('public')->exists($address->image_url)) {
                Storage::disk('public')->delete($address->image_url);
            }
            $imageUrl = $request->file('image')->store('addresses', 'public');
        }

        return new UpdateAddressRequestEntity(
            addressId: $addressId,
            label: $request->input('label'),
            inputMode: $request->input('input_mode'),
            postalCode: $request->input('postal_code'),
            phone: $request->input('phone'),
            imageUrl: $imageUrl,
            isDefault: $request->has('is_default') ? (bool) $request->input('is_default') : null,
            detail: $detail,
        );
    }
}
