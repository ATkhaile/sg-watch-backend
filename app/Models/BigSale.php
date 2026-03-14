<?php

namespace App\Models;

use App\Components\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BigSale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'media_url',
        'media_type',
        'product_ids',
        'sale_start_date',
        'sale_end_date',
        'sale_percentage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'product_ids' => 'array',
            'sale_start_date' => 'date',
            'sale_end_date' => 'date',
            'sale_percentage' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected $appends = ['media_full_url'];

    public function getMediaFullUrlAttribute(): ?string
    {
        if (!$this->media_url) {
            return null;
        }
        return CommonComponent::getFullUrl($this->media_url);
    }
}
