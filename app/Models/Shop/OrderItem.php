<?php

namespace App\Models\Shop;

use App\Models\Shop\ProductColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'shop_order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_color_id',
        'product_name',
        'product_color_name',
        'product_sku',
        'product_image',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:0',
            'total_price' => 'decimal:0',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }
}
