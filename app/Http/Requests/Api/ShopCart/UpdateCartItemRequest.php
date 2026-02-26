<?php

namespace App\Http\Requests\Api\ShopCart;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateCartItemRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];

        if (!auth()->check()) {
            $rules['device_id'] = ['required', 'string', 'max:255'];
        } else {
            $rules['device_id'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }
}
