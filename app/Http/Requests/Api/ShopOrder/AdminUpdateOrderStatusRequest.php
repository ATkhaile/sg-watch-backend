<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Enums\OrderStatus;
use App\Http\Requests\Api\ApiFormRequest;

class AdminUpdateOrderStatusRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:' . implode(',', OrderStatus::getValues())],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'shipping_carrier' => ['nullable', 'string', 'max:255'],
            'cancel_reason' => ['nullable', 'string', 'max:1000'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
