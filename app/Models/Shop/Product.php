<?php

namespace App\Models\Shop;

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
        'short_description',
        'description',
        'product_info',
        'deal_info',
        'price_jpy',
        'price_vnd',
        'original_price_jpy',
        'original_price_vnd',
        'points',
        'gender',
        'movement_type',
        'condition',
        'attributes',
        'stock_quantity',
        'warranty_months',
        'is_active',
        'is_featured',
        'sort_order',
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
            'average_rating' => 'decimal:1',
            'attributes' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected $appends = ['primary_image_url'];

    // =========================================================================
    // Accessors
    // =========================================================================

    public function getPrimaryImageUrlAttribute(): ?string
    {
        return $this->images()->where('is_primary', true)->value('image_url')
            ?? $this->images()->orderBy('sort_order')->value('image_url');
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
        return $this->hasOne(ProductImage::class, 'product_id')->where('is_primary', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id')->where('is_approved', true);
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
}
