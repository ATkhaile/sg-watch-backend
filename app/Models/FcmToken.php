<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class FcmToken extends Model implements AuditableContract
{
    use HasFactory;
    use SoftDeletes;
    use Auditable;

    protected $table = 'fcm_tokens';

    protected $fillable = [
        'user_id',
        'fcm_token',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_notifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
