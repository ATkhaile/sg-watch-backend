<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class UserNotification extends Model implements AuditableContract
{
    use SoftDeletes;
    use HasFactory;
    use Auditable;

    protected $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'fcm_token_id',
        'notification_id',
        'line_user_id',
        'push_type',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'push_type' => 'string',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function fcm_token()
    {
        return $this->belongsTo(FcmToken::class);
    }


    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
