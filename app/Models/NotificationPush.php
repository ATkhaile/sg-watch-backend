<?php

namespace App\Models;

use App\Components\CommonComponent;
use App\Enums\StorageFolder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Reliese\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class NotificationPush extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;

    protected $table = 'notification_pushs';

    protected $fillable = [
        'title',
        'message',
        'img_path',
        'all_user_flag',
        'push_now_flag',
        'push_schedule',
        'created_by',
        'process',
        'sound',
        'redirect_type',
        'app_page_id',
        'attach_file',
        'attach_link',
    ];

    protected $casts = [
        'all_user_flag' => 'boolean',
        'push_now_flag' => 'boolean',
        'push_schedule' => 'datetime:Y/m/d H:i',
        'created_at'    => 'datetime:Y/m/d H:i:s',
        'updated_at'    => 'datetime:Y/m/d H:i:s',
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notification_pushs')
            ->withTimestamps();
    }
    public function appPage()
    {
        return $this->belongsTo(AppPage::class, 'app_page_id');
    }
    protected $appends = ['img_url', 'attach_file_url'];

    public function getImgUrlAttribute(): ?string
    {
        if (!$this->img_path) {
            return null;
        }
        return CommonComponent::getFullUrl(
            StorageFolder::NOTIFICATION_PUSH . '/' . $this->img_path
        );
    }
    public function getAttachFileUrlAttribute(): ?string
    {
        if (!$this->attach_file) {
            return null;
        }

        return CommonComponent::getFullUrl(
            StorageFolder::NOTIFICATION_PUSH . '/' . $this->attach_file
        );
    }
}
