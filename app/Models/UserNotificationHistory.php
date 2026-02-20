<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class UserNotificationHistory extends Model implements AuditableContract
{
    use SoftDeletes;
    use HasFactory;
    use Auditable;

    protected $table = 'user_notification_histories';

    protected $fillable = [
        'user_id',
        'notification_push_id',
        'fcm_token_id',
    ];
    protected $casts = [
        'created_at'    => 'datetime:Y/m/d H:i:s',
        'updated_at'    => 'datetime:Y/m/d H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notificationPush()
    {
        return $this->belongsTo(NotificationPush::class);
    }

    public function fcmToken()
    {
        return $this->belongsTo(FcmToken::class);
    }
}
