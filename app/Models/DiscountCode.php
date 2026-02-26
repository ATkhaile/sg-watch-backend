<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCode extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'quantity',
        'percentage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'percentage' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
