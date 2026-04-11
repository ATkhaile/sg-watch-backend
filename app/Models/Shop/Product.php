<?php

namespace App\Models\Shop;

use App\Components\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop_products';

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'primary_image',
        'short_description',
        'description',
        'product_info',
        'deal_info',
        'price_jpy',
        'price_vnd',
        'original_price_jpy',
        'original_price_vnd',
        'cost_price_jpy',
        'points',
        'gender',
        'movement_type',
        'condition',
        'attributes',
        'stock_quantity',
        'stock_type',
        'warranty_months',
        'is_active',
        'is_featured',
        'is_domestic',
        'is_new',
        'sale_percent',
        'sort_order',
        'display_order',
        'average_rating',
        'review_count',
        'view_count',
        'sold_count',
    ];

    protected function casts(): array
    {
        return [
            'price_jpy' => 'decimal:0',
            'price_vnd' => 'decimal:0',
            'original_price_jpy' => 'decimal:0',
            'original_price_vnd' => 'decimal:0',
            'cost_price_jpy' => 'decimal:0',
            'average_rating' => 'decimal:1',
            'attributes' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_domestic' => 'boolean',
            'is_new' => 'boolean',
        ];
    }

    protected $appends = ['primary_image_url'];

    private const JPY_TO_VND_RATE = 175;

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (!$product->display_order) {
                $query = $product->brand_id
                    ? Product::where('brand_id', $product->brand_id)
                    : Product::where('category_id', $product->category_id);
                $product->display_order = ($query->max('display_order') ?? 0) + 1;
            }
        });

        static::saving(function (Product $product) {
            if ($product->isDirty('price_jpy')) {
                $product->price_vnd = $product->price_jpy * self::JPY_TO_VND_RATE;
            }
            if ($product->isDirty('original_price_jpy')) {
                $product->original_price_vnd = $product->original_price_jpy
                    ? $product->original_price_jpy * self::JPY_TO_VND_RATE
                    : null;
            }
            if ($product->isDirty('price_jpy') || $product->isDirty('original_price_jpy')) {
                $original = $product->original_price_jpy;
                $selling = $product->price_jpy;
                $product->sale_percent = ($original && $selling && $original > 0)
                    ? (int) round((($original - $selling) / $original) * 100)
                    : null;
            }
            if ($product->isDirty('price_jpy') || $product->isDirty('cost_price_jpy')) {
                $product->points = self::calculatePoints(
                    (int) $product->price_jpy,
                    (int) $product->cost_price_jpy
                );
            }
        });
    }

    // =========================================================================
    // Accessors
    // =========================================================================

    public function getPrimaryImageUrlAttribute(): ?string
    {
        return $this->primary_image ? CommonComponent::getFullUrl($this->primary_image) : null;
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class, 'product_id')->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id')->where('is_approved', true);
    }

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class, 'product_id')->orderBy('sort_order');
    }

    // =========================================================================
    // Methods
    // =========================================================================

    public function updateRatingStats(): void
    {
        $stats = $this->approvedReviews()
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as count')
            ->first();

        $this->update([
            'average_rating' => round($stats->avg_rating ?? 0, 1),
            'review_count' => $stats->count ?? 0,
        ]);
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public static function calculatePoints(int $priceJpy, int $costPriceJpy): int
    {
        if (!$priceJpy || !$costPriceJpy) {
            return 0;
        }

        $profit = $priceJpy - $costPriceJpy;

        if ($profit > 12000) {
            return 1000;
        } elseif ($profit >= 10000) {
            return 500;
        }

        return 200;
    }
}
