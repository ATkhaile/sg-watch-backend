<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Http\Requests\Api\ApiFormRequest;

class CancelOrderRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cancel_reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}
