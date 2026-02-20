<?php

namespace App\Models;

use App\Components\CommonComponent;
use App\Enums\Boolean;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Illuminate\Support\Str;

/**
 * User Model
 *
 * 統合ユーザーモデル（管理者・一般ユーザー両方に対応）
 * is_system_admin で admin（管理者）と user（一般ユーザー）を区別
 */
class User extends Authenticatable implements JWTSubject, AuditableContract
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Auditable;

    protected $fillable = [
        'uuid',
        'email',
        'password',
        'first_name',
        'last_name',
        'gender',
        'avatar_url',
        'birthday',
        'is_system_admin',
        'remember_token',
        'reset_password_token',
        'reset_password_token_expire',
        'email_verified_at',
        'invite_code',
        'inviter_id',
        'invited_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime:Y/m/d H:i:s',
            'password' => 'hashed',
            'reset_password_token_expire' => 'datetime',
            'invited_at' => 'datetime:Y/m/d H:i:s',
            'birthday' => 'date:Y-m-d',
            'is_system_admin' => 'boolean',
        ];
    }

    protected $appends = ['full_name', 'avatar_full_url'];

    protected function serializeDate($date)
    {
        return Carbon::parse($date);
    }

    // =========================================================================
    // JWT
    // =========================================================================

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // =========================================================================
    // Boot
    // =========================================================================

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = Str::uuid()->toString();
            }
        });
        static::deleting(function ($user) {
            $timeFormat = Carbon::now()->format('YmdHis');
            $user->email = $user->email . '_' . $timeFormat;
            $user->invite_code = $user->invite_code . '_' . $timeFormat;
            $user->save();
        });
        static::created(function ($user) {
            $user->userCommunitySetting()->create();
        });
    }

    // =========================================================================
    // Accessors
    // =========================================================================

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getAvatarFullUrlAttribute(): ?string
    {
        if (!$this->avatar_url) {
            return null;
        }

        return CommonComponent::getFullUrl($this->avatar_url);
    }

    // =========================================================================
    // RBAC
    // =========================================================================

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSystemAdmin()) {
            return true;
        }

        if ($this->permissions()
            ->where('name', $permission)
            ->where('is_active', true)
            ->exists()) {
            return true;
        }

        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission)
                  ->where('is_active', true);
        })->exists();
    }

    public function isSystemAdmin(): bool
    {
        return (bool) $this->is_system_admin;
    }

    // =========================================================================
    // Invite
    // =========================================================================

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invitees(): HasMany
    {
        return $this->hasMany(User::class, 'inviter_id');
    }

    // =========================================================================
    // Community
    // =========================================================================

    public function followings(): HasMany
    {
        return $this->hasMany(UserFollow::class, 'follower_id', 'id');
    }

    public function storiesFlag(): HasMany
    {
        return $this->hasMany(CommunityPost::class, 'created_by')->where('flag_story', Boolean::TRUE)->where('story_expired', '>', Carbon::now());
    }

    public function userCommunitySetting(): HasOne
    {
        return $this->hasOne(UserCommunitySetting::class, 'user_id');
    }

    public function mutedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_mutes', 'muter_id', 'muted_id')
            ->withTimestamps();
    }

    public function mutingUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_mutes', 'muted_id', 'muter_id')
            ->withTimestamps();
    }

    // =========================================================================
    // Credentials
    // =========================================================================

    public function credentials(): HasMany
    {
        return $this->hasMany(UserCredential::class);
    }

    public function primaryCredential(): HasOne
    {
        return $this->hasOne(UserCredential::class)->where('is_primary', true);
    }

    public function lineCredential(): HasOne
    {
        return $this->hasOne(UserCredential::class)->where('provider', UserCredential::PROVIDER_LINE);
    }

    public function emailCredential(): HasOne
    {
        return $this->hasOne(UserCredential::class)->where('provider', UserCredential::PROVIDER_EMAIL);
    }

    public function hasLineAuth(): bool
    {
        return $this->lineCredential()->exists();
    }

    public function hasEmailAuth(): bool
    {
        return $this->emailCredential()->exists();
    }

    // =========================================================================
    // Membership & Entitlement
    // =========================================================================

    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(Membership::class, 'membership_users')
            ->withPivot(['id', 'granted_at', 'expires_at', 'granted_by', 'granted_by_id'])
            ->withTimestamps();
    }

    public function hasMembership(string $membershipName): bool
    {
        return $this->memberships()
            ->where('name', $membershipName)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('membership_users.expires_at')
                    ->orWhere('membership_users.expires_at', '>', now());
            })
            ->exists();
    }

    public function getAllRoles(): \Illuminate\Support\Collection
    {
        $directRoles = $this->roles()->get();

        $membershipRoles = $this->memberships()
            ->where('memberships.is_active', true)
            ->where(function ($query) {
                $query->whereNull('membership_users.expires_at')
                    ->orWhere('membership_users.expires_at', '>', now());
            })
            ->with('roles')
            ->get()
            ->pluck('roles')
            ->flatten();

        return $directRoles->merge($membershipRoles)->unique('id');
    }

    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        $directPermissions = $this->permissions()->get();

        $rolePermissions = $this->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten();

        $membershipPermissions = $this->memberships()
            ->where('memberships.is_active', true)
            ->where(function ($query) {
                $query->whereNull('membership_users.expires_at')
                    ->orWhere('membership_users.expires_at', '>', now());
            })
            ->with(['permissions', 'roles.permissions'])
            ->get()
            ->map(function ($membership) {
                $permissions = $membership->permissions;
                $rolePermissions = $membership->roles->pluck('permissions')->flatten();
                return $permissions->merge($rolePermissions);
            })
            ->flatten();

        return $directPermissions->merge($rolePermissions)->merge($membershipPermissions)->unique('id');
    }

    public function hasPermissionIncludingMembership(string $permission): bool
    {
        if ($this->isSystemAdmin()) {
            return true;
        }

        return $this->getAllPermissions()->contains('name', $permission);
    }

    public function entitlements(): HasMany
    {
        return $this->hasMany(UserEntitlement::class, 'user_id');
    }

    public function validEntitlements(): HasMany
    {
        return $this->entitlements()
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function entitlementLogs(): HasMany
    {
        return $this->hasMany(UserEntitlementLog::class, 'user_id');
    }

    public function hasEntitlement(string $entitlementCode): bool
    {
        $entitlement = $this->validEntitlements()
            ->where('is_enabled', true)
            ->whereHas('entitlementType', function ($query) use ($entitlementCode) {
                $query->where('code', $entitlementCode)
                    ->where('is_active', true);
            })
            ->first();

        if (!$entitlement) {
            return false;
        }

        return $entitlement->getEffectiveBoolValue();
    }

    public function getEntitlementValue(string $entitlementCode): ?int
    {
        $entitlement = $this->validEntitlements()
            ->where('is_enabled', true)
            ->whereHas('entitlementType', function ($query) use ($entitlementCode) {
                $query->where('code', $entitlementCode)
                    ->where('is_active', true);
            })
            ->first();

        if (!$entitlement) {
            return null;
        }

        return $entitlement->getEffectiveNumericValue();
    }

    public function getEntitlement(string $entitlementCode): ?UserEntitlement
    {
        return $this->validEntitlements()
            ->where('is_enabled', true)
            ->whereHas('entitlementType', function ($query) use ($entitlementCode) {
                $query->where('code', $entitlementCode)
                    ->where('is_active', true);
            })
            ->first();
    }

    // =========================================================================
    // Other Relations
    // =========================================================================

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function aiAppMessages(): HasMany
    {
        return $this->hasMany(AiAppMessage::class, 'from_account_id');
    }

    public function suspensionLogs(): HasMany
    {
        return $this->hasMany(UserSuspensionLog::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function defaultAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class)->where('is_default', true);
    }
}
