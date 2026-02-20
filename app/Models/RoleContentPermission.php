<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class RoleContentPermission
 * ロールベースのコンテンツCRUD権限（ポリモーフィック）
 *
 * @property int $id
 * @property int $role_id
 * @property int $permissionable_id
 * @property string $permissionable_type
 * @property bool $can_create
 * @property bool $can_read
 * @property bool $can_update
 * @property bool $can_delete
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Role $role
 * @property Model $permissionable
 *
 * @package App\Models
 */
class RoleContentPermission extends Model
{
    protected $table = 'role_content_permissions';

    protected $casts = [
        'role_id' => 'integer',
        'can_create' => 'boolean',
        'can_read' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
    ];

    protected $fillable = [
        'role_id',
        'permissionable_id',
        'permissionable_type',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    /**
     * ロール
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * 対象コンテンツ（ポリモーフィック）
     */
    public function permissionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 指定した操作の権限があるかチェック
     */
    public function can(string $action): bool
    {
        return match ($action) {
            'create' => $this->can_create,
            'read' => $this->can_read,
            'update' => $this->can_update,
            'delete' => $this->can_delete,
            default => false,
        };
    }

    /**
     * Scope: 特定のロールに絞り込み
     */
    public function scopeForRole($query, int $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /**
     * Scope: 特定のコンテンツタイプに絞り込み
     */
    public function scopeForContentType($query, string $type)
    {
        return $query->where('permissionable_type', $type);
    }

    /**
     * Scope: 読み取り権限があるもの
     */
    public function scopeCanRead($query)
    {
        return $query->where('can_read', true);
    }
}
