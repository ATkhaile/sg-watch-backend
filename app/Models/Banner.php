<?php

namespace App\Models;

use App\Components\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'media_url',
        'media_type',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
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
