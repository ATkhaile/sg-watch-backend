<?php

namespace App\Http\Requests\Api\ShopFavorite;

use App\Http\Requests\Api\ApiFormRequest;

class ToggleFavoriteRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:shop_products,id'],
        ];
    }
}
