<?php

namespace App\Http\Requests\Api\ShopCart;

use App\Http\Requests\Api\ApiFormRequest;

class RemoveCartItemRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (!auth()->check()) {
            return ['device_id' => ['required', 'string', 'max:255']];
        }

        return ['device_id' => ['nullable', 'string', 'max:255']];
    }
}
