<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
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
            'order_number' => ['nullable', 'string', 'max:255'],
            'user_keyword' => ['nullable', 'string', 'max:255'],
            'product_keyword' => ['nullable', 'string', 'max:255'],
            'order_type' => ['nullable', 'string', 'in:' . implode(',', OrderType::getValues())],
            'status' => ['nullable', 'string', 'in:' . implode(',', OrderStatus::getValues())],
            'payment_status' => ['nullable', 'string', 'in:' . implode(',', PaymentStatus::getValues())],
            'payment_method' => ['nullable', 'string', 'in:' . implode(',', PaymentMethod::getValues())],
            'brand_id' => ['nullable', 'integer', 'exists:shop_brands,id'],
            'category_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
            'date_from' => ['nullable', 'date', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:date_from'],
            'sort_by' => ['nullable', 'string', 'in:newest,oldest,total_asc,total_desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
