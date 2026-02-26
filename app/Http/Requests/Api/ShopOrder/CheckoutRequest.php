<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Enums\PaymentMethod;
use App\Enums\ShippingMethod;
use App\Http\Requests\Api\ApiFormRequest;

class CheckoutRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'address_id' => [
                'required',
                'integer',
                'exists:user_addresses,id',
                function ($attribute, $value, $fail) use ($userId) {
                    $exists = \App\Models\UserAddress::where('id', $value)
                        ->where('user_id', $userId)
                        ->exists();
                    if (!$exists) {
                        $fail('The selected address does not belong to you.');
                    }
                },
            ],
            'payment_method' => ['required', 'string', 'in:' . implode(',', PaymentMethod::getValues())],
            'shipping_method' => ['required', 'string', 'in:' . implode(',', ShippingMethod::getValues())],
            'payment_receipt' => ['nullable', 'file', 'image', 'max:5120'],
            'note' => ['nullable', 'string', 'max:1000'],
            'currency' => ['nullable', 'string', 'in:JPY,VND'],
            'discount_code' => ['nullable', 'string', 'max:255'],
            'use_points' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
