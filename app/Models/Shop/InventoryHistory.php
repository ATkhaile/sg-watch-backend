<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Shop\ProductColor;

class InventoryHistory extends Model
{
    protected $table = 'shop_inventory_histories';

    protected $fillable = [
        'product_id',
        'product_color_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'note',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }
}
