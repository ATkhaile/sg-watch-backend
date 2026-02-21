<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPointSetting extends Model
{
    use SoftDeletes;

    protected $table = 'user_point_settings';

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'point',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'point' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
