<?php

namespace App\Http\Requests\Api\ShopProduct;

use App\Enums\MovementType;
use App\Enums\ProductCondition;
use App\Enums\ProductGender;
use App\Http\Requests\Api\ApiFormRequest;

class UpdateProductRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('id');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'sku' => ['sometimes', 'string', 'max:100', 'unique:shop_products,sku,' . $productId],
            'slug' => ['nullable', 'string', 'max:255', 'unique:shop_products,slug,' . $productId],
            'category_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:shop_brands,id'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'product_info' => ['nullable', 'string'],
            'deal_info' => ['nullable', 'string'],
            'price_jpy' => ['nullable', 'numeric', 'min:0'],
            'price_vnd' => ['nullable', 'numeric', 'min:0'],
            'original_price_jpy' => ['nullable', 'numeric', 'min:0'],
            'original_price_vnd' => ['nullable', 'numeric', 'min:0'],
            'points' => ['nullable', 'integer', 'min:0'],
            'gender' => ['nullable', 'string', 'in:' . implode(',', ProductGender::getValues())],
            'movement_type' => ['nullable', 'string', 'in:' . implode(',', MovementType::getValues())],
            'condition' => ['nullable', 'string', 'in:' . implode(',', ProductCondition::getValues())],
            'attributes' => ['nullable', 'array'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'warranty_months' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'existing_image_ids' => ['nullable', 'array'],
            'existing_image_ids.*' => ['integer'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
        ];
    }
}
