<?php

namespace App\Http\Requests\Api\ShopCollection;

use App\Http\Requests\Api\ApiFormRequest;

class CreateCollectionRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'integer', 'exists:shop_products,id'],
        ];
    }
}
