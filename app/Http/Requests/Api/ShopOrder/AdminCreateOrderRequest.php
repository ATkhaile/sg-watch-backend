<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ShippingMethod;
use App\Http\Requests\Api\ApiFormRequest;

class AdminCreateOrderRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:shop_products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'in:JPY,VND'],
            'payment_method' => ['required', 'string', 'in:' . implode(',', PaymentMethod::getValues())],
            'shipping_method' => ['required', 'string', 'in:' . implode(',', ShippingMethod::getValues())],
            'status' => ['nullable', 'string', 'in:' . implode(',', OrderStatus::getValues())],
            'payment_status' => ['nullable', 'string', 'in:' . implode(',', PaymentStatus::getValues())],
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['nullable', 'string', 'max:50'],
            'shipping_email' => ['nullable', 'string', 'email', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['nullable', 'string', 'max:255'],
            'shipping_country' => ['nullable', 'string', 'max:10'],
            'shipping_postal_code' => ['nullable', 'string', 'max:20'],
            'shipping_fee' => ['nullable', 'numeric', 'min:0'],
            'cod_fee' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
