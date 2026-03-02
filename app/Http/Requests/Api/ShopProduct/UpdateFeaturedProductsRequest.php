<?php

namespace App\Http\Requests\Api\ShopProduct;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateFeaturedProductsRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_ids' => ['required', 'array', 'max:8'],
            'product_ids.*' => ['required', 'integer', 'exists:shop_products,id'],
        ];
    }
}
