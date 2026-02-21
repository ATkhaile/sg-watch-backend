<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointMaster extends Model
{
    use SoftDeletes;

    protected $table = 'point_masters';

    protected $fillable = [
        'type',
        'status',
        'start_date',
        'end_date',
        'point',
        'inviter_point',
        'invitee_point',
    ];

    protected $casts = [
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'point' => 'integer',
        'inviter_point' => 'integer',
        'invitee_point' => 'integer',
    ];
}
