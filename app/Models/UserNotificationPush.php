<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class UserNotificationPush extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'user_notification_pushs';

    protected $fillable = [
        'notification_push_id',
        'user_id',
    ];
    public function notificationPush()
    {
        return $this->belongsTo(NotificationPush::class, 'notification_push_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
