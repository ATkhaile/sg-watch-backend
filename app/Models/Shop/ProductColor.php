<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductColor extends Model
{
    use SoftDeletes;

    protected $table = 'shop_product_colors';

    protected $fillable = [
        'product_id',
        'color_code',
        'color_name',
        'sku',
        'price_jpy',
        'price_vnd',
        'original_price_jpy',
        'original_price_vnd',
        'cost_price_jpy',
        'sale_percent',
        'points',
        'stock_quantity',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_jpy' => 'decimal:0',
            'price_vnd' => 'decimal:0',
            'original_price_jpy' => 'decimal:0',
            'original_price_vnd' => 'decimal:0',
            'cost_price_jpy' => 'decimal:0',
            'is_active' => 'boolean',
        ];
    }

    private const JPY_TO_VND_RATE = 175;

    protected static function booted(): void
    {
        static::saving(function (ProductColor $color) {
            if ($color->isDirty('price_jpy')) {
                $color->price_vnd = $color->price_jpy * self::JPY_TO_VND_RATE;
            }
            if ($color->isDirty('original_price_jpy')) {
                $color->original_price_vnd = $color->original_price_jpy
                    ? $color->original_price_jpy * self::JPY_TO_VND_RATE
                    : null;
            }
            if ($color->isDirty('price_jpy') || $color->isDirty('original_price_jpy')) {
                $original = $color->original_price_jpy;
                $selling = $color->price_jpy;
                $color->sale_percent = ($original && $selling && $original > 0)
                    ? (int) round((($original - $selling) / $original) * 100)
                    : null;
            }
            if ($color->isDirty('price_jpy') || $color->isDirty('cost_price_jpy')) {
                $color->points = \App\Models\Shop\Product::calculatePoints(
                    (int) $color->price_jpy,
                    (int) $color->cost_price_jpy
                );
            }
        });
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductColorImage::class, 'product_color_id')->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductColorImage::class, 'product_color_id')
            ->where('is_primary', true)
            ->orderBy('sort_order');
    }
}
