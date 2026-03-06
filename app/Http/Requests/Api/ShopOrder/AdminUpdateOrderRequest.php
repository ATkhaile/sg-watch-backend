<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Enums\PaymentMethod;
use App\Enums\ShippingMethod;
use App\Http\Requests\Api\ApiFormRequest;

class AdminUpdateOrderRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['nullable', 'array', 'min:1'],
            'items.*.product_id' => ['required_with:items', 'integer', 'exists:shop_products,id'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'in:JPY,VND'],
            'payment_method' => ['nullable', 'string', 'in:' . implode(',', PaymentMethod::getValues())],
            'shipping_method' => ['nullable', 'string', 'in:' . implode(',', ShippingMethod::getValues())],
            'shipping_name' => ['nullable', 'string', 'max:255'],
            'shipping_phone' => ['nullable', 'string', 'max:50'],
            'shipping_email' => ['nullable', 'string', 'email', 'max:255'],
            'shipping_address' => ['nullable', 'string', 'max:500'],
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
