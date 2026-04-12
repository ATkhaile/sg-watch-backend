<?php

namespace App\Http\Requests\Api\ShopCollection;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateCollectionRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'sort_order'   => ['nullable', 'integer', 'min:0'],
            'is_active'    => ['nullable', 'boolean'],
            'product_ids'  => ['nullable', 'array'],
            'product_ids.*' => ['required', 'integer', 'exists:shop_products,id'],
        ];
    }
}
