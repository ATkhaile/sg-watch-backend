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
            'id' => ['required', 'integer', 'exists:shop_products,id'],
            'display_order' => ['required', 'integer', 'min:1'],
        ];
    }
}
