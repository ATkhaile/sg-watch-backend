<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $table = 'shop_cart_items';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price_at_addition',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'price_at_addition' => 'decimal:0',
        ];
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getSubtotalAttribute(): float
    {
        return $this->price_at_addition * $this->quantity;
    }
}
