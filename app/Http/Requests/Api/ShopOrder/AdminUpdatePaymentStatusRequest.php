<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Http\Requests\Api\ApiFormRequest;

class AdminUpdatePaymentStatusRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_status' => ['required', 'string', 'in:pending,paid'],
        ];
    }
}
