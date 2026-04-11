<?php

namespace App\Http\Requests\Api\ShopProductColor;

use App\Http\Requests\Api\ApiFormRequest;

class CreateProductColorRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:shop_products,id'],
            'color_code' => ['required', 'string', 'max:50'],
            'color_name' => ['required', 'string', 'max:100'],
            'sku' => ['nullable', 'string', 'max:100'],
            'price_jpy' => ['required', 'numeric', 'min:0'],
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
        ];
    }
}
