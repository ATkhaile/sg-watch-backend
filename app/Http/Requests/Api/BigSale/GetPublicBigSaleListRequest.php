<?php

namespace App\Http\Requests\Api\BigSale;

use App\Http\Requests\Api\ApiFormRequest;

class GetPublicBigSaleListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
