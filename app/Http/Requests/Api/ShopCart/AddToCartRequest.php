<?php

namespace App\Http\Requests\Api\ShopCart;

use App\Http\Requests\Api\ApiFormRequest;

class AddToCartRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'product_id' => ['required', 'integer', 'exists:shop_products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'currency' => ['nullable', 'string', 'in:JPY,VND'],
        ];

        // device_id required when not logged in
        if (!auth()->check()) {
            $rules['device_id'] = ['required', 'string', 'max:255'];
        } else {
            $rules['device_id'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }
}
