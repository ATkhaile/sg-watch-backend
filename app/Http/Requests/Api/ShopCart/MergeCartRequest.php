<?php

namespace App\Http\Requests\Api\ShopCart;

use App\Http\Requests\Api\ApiFormRequest;

class MergeCartRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string', 'max:255'],
        ];
    }
}
