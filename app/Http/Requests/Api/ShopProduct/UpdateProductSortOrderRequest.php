<?php

namespace App\Http\Requests\Api\ShopProduct;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateProductSortOrderRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'exists:shop_products,id'],
            'products.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
