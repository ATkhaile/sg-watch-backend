<?php

namespace App\Models\Shop;

use App\Components\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop_brands';

    protected $fillable = [
        'name',
        'slug',
        'logo_url',
        'description',
        'country',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected $appends = ['logo_full_url'];

    public function getLogoFullUrlAttribute(): ?string
    {
        if (!$this->logo_url) {
            return null;
        }
        return CommonComponent::getFullUrl($this->logo_url);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
