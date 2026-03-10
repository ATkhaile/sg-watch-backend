<?php

namespace App\Http\Requests\Api\ShopOrder;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
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
        $orderType = $this->input('order_type', OrderType::ONLINE);

        $rules = [
            'order_type' => ['nullable', 'string', 'in:' . implode(',', OrderType::getValues())],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:shop_products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'in:JPY,VND'],
            'payment_method' => ['nullable', 'string', 'in:' . implode(',', PaymentMethod::getValues())],
            'shipping_method' => ['nullable', 'string', 'in:' . implode(',', ShippingMethod::getValues())],
            'status' => ['nullable', 'string', 'in:' . implode(',', OrderStatus::getValues())],
            'payment_status' => ['nullable', 'string', 'in:' . implode(',', PaymentStatus::getValues())],
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
            'discount_code' => ['nullable', 'string', 'max:255'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ];

        // Đơn hàng online: bắt buộc user_id, shipping_name, shipping_address, payment_method, shipping_method
        if ($orderType === OrderType::ONLINE) {
            $rules['user_id'] = ['required', 'integer', 'exists:users,id'];
            $rules['customer_name'] = ['nullable', 'string', 'max:255'];
            $rules['shipping_name'] = ['required', 'string', 'max:255'];
            $rules['shipping_address'] = ['required', 'string', 'max:500'];
            $rules['payment_method'] = ['required', 'string', 'in:' . implode(',', PaymentMethod::getValues())];
            $rules['shipping_method'] = ['required', 'string', 'in:' . implode(',', ShippingMethod::getValues())];
        }

        // Đơn hàng walk-in: bắt buộc customer_name, không cần user_id
        // payment_method mặc định là tiền mặt (cash), shipping_method mặc định là nhận tại cửa hàng (pickup)
        if ($orderType === OrderType::WALK_IN) {
            $rules['user_id'] = ['nullable', 'integer', 'exists:users,id'];
            $rules['customer_name'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }
}
