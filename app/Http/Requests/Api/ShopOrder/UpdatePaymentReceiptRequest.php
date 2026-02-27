<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Http\Requests\Api\ApiFormRequest;

class UpdatePaymentReceiptRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_receipt' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:51200'],
        ];
    }
}
