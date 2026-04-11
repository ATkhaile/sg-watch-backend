<?php

namespace App\Http\Requests\Api\Dashboard;

use App\Enums\OrderStatus;
use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class AdminGetDashboardRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter_type' => ['nullable', 'string', 'in:date_range,month,year'],
            'date_from' => ['nullable', 'date', 'date_format:Y-m-d', 'required_if:filter_type,date_range'],
            'date_to' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:date_from', 'required_if:filter_type,date_range'],
            'month' => ['nullable', 'date_format:Y-m', 'required_if:filter_type,month'],
            'year' => ['nullable', 'integer', 'min:2020', 'max:2100', 'required_if:filter_type,year'],
            'order_status' => ['nullable', 'array'],
            'order_status.*' => ['string', Rule::in(OrderStatus::getValues())],
        ];
    }
}
