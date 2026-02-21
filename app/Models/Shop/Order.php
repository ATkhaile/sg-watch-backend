<?php

namespace App\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop_orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'payment_status',
        'payment_method',
        'shipping_method',
        'subtotal',
        'shipping_fee',
        'discount_amount',
        'total_amount',
        'currency',
        'coupon_code',
        'shipping_name',
        'shipping_phone',
        'shipping_email',
        'shipping_address',
        'shipping_city',
        'shipping_country',
        'shipping_postal_code',
        'note',
        'admin_note',
        'tracking_number',
        'shipping_carrier',
        'confirmed_at',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:0',
            'shipping_fee' => 'decimal:0',
            'discount_amount' => 'decimal:0',
            'total_amount' => 'decimal:0',
            'confirmed_at' => 'datetime',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = static::whereDate('created_at', today())
            ->orderByDesc('id')
            ->first();

        $sequence = $lastOrder
            ? (int) substr($lastOrder->order_number, -4) + 1
            : 1;

        return sprintf('SO-%s-%04d', $date, $sequence);
    }
}
