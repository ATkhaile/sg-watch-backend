<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Enums\OrderStatus;
use App\Http\Requests\Api\ApiFormRequest;

class GetOrderListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', 'in:' . implode(',', OrderStatus::getValues())],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}
