<?php

namespace App\Models;

use App\Components\CommonComponent;
use App\Enums\StorageFolder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Notification extends Model implements AuditableContract
{
    use SoftDeletes;
    use HasFactory;
    use Auditable;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'content',
        'push_type',
        'push_datetime',
        'push_now_flag',
        'image_file',
        'sender_type'
    ];

    protected $casts = [
        'push_datetime' => 'datetime',
        'read_at' => 'datetime',
        'push_now_flag' => 'boolean',
        'push_type' => 'string',
        'sender_type' => 'string',
    ];

    protected $appends = ['image_url'];


    public function user_notifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image_file) {
            return;
        }
        return CommonComponent::getFullUrl(StorageFolder::Image . '/' . $this->create_user_id . '/' . $this->image_file);
    }
}
