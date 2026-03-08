<?php

namespace App\Http\Requests\Api\Dashboard;

use App\Http\Requests\Api\ApiFormRequest;

class AdminGetDashboardRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter_type' => ['nullable', 'string', 'in:day,month,year'],
            'date' => ['nullable', 'date', 'date_format:Y-m-d', 'required_if:filter_type,day'],
            'month' => ['nullable', 'date_format:Y-m', 'required_if:filter_type,month'],
            'year' => ['nullable', 'integer', 'min:2020', 'max:2100', 'required_if:filter_type,year'],
        ];
    }
}
