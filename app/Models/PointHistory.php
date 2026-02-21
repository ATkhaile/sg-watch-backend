<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointHistory extends Model
{
    use SoftDeletes;

    protected $table = 'point_histories';

    protected $fillable = [
        'user_id',
        'point',
        'memo',
        'point_type',
        'show_popup_flag',
        'last_update_user_id',
    ];

    protected $casts = [
        'point' => 'integer',
        'show_popup_flag' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastUpdateUser()
    {
        return $this->belongsTo(User::class, 'last_update_user_id');
    }
}
