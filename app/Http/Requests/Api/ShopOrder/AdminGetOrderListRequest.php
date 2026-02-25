<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Http\Requests\Api\ApiFormRequest;

class AdminGetOrderListRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:pending,confirmed,processing,shipping,delivered,completed,cancelled,refunded'],
            'payment_status' => ['nullable', 'string', 'in:pending,paid,failed,refunded'],
            'payment_method' => ['nullable', 'string', 'in:cod,bank_transfer,deposit_transfer,stripe'],
            'sort_by' => ['nullable', 'string', 'in:newest,oldest,total_asc,total_desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
