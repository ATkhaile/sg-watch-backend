<?php

namespace App\Http\Requests\Api\ShopProductColor;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateProductColorRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'color_code' => ['nullable', 'string', 'max:50'],
            'color_name' => ['nullable', 'string', 'max:100'],
            'sku' => ['nullable', 'string', 'max:100'],
            'price_jpy' => ['nullable', 'numeric', 'min:0'],
            'original_price_jpy' => ['nullable', 'numeric', 'min:0'],
            'cost_price_jpy' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'images' => ['nullable', 'array'],
            'images.*' => ['sometimes', 'file', 'image', 'max:5120'],
            'images.*.image_url' => ['sometimes', 'string', 'max:500'],
            'images.*.alt_text' => ['nullable', 'string', 'max:255'],
            'images.*.is_primary' => ['nullable', 'boolean'],
            'images.*.sort_order' => ['nullable', 'integer'],
            'existing_image_ids' => ['nullable', 'array'],
            'existing_image_ids.*' => ['integer'],
        ];
    }
}
