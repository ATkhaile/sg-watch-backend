<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class StripeAccount extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;

    protected $table = 'stripe_accounts';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $casts = [
        'parent_account_id' => 'int',
        'payout_settings' => 'array',
        'requirements_currently_due' => 'array',
        'charges_enabled' => 'boolean',
        'payouts_enabled' => 'boolean',
        'is_test_mode' => 'boolean',
        'stripe_created' => 'datetime',
        'last_connected_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'creator_id' => 'int',
        'updater_id' => 'int',
    ];

    protected $fillable = [
        'uuid',
        'status',
        'display_name',
        'account_type',
        'parent_account_id',
        'object_type',
        'email',
        'stripe_id',
        'business_profile_name',
        'business_profile_product_description',
        'business_type',
        'country',
        'currency',
        'payout_settings',
        'requirements_currently_due',
        'charges_enabled',
        'payouts_enabled',
        'public_key',
        'secret_key',
        'webhook_secret',
        'is_test_mode',
        'stripe_created',
        'last_connected_at',
        'last_synced_at',
        'creator_id',
        'updater_id',
    ];

    protected $hidden = [
        'public_key',
        'secret_key',
        'webhook_secret',
    ];

    /**
     * UUIDでレコードを検索
     */
    public static function findByUuid(string $uuid): ?self
    {
        return static::where('uuid', $uuid)->first();
    }

    /**
     * Stripe IDでレコードを検索
     */
    public static function findByStripeId(string $stripeId): ?self
    {
        return static::where('stripe_id', $stripeId)->first();
    }

    /**
     * 親アカウント
     */
    public function parentAccount()
    {
        return $this->belongsTo(self::class, 'parent_account_id');
    }

    /**
     * 子アカウント（Connect）
     */
    public function childAccounts()
    {
        return $this->hasMany(self::class, 'parent_account_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updater_id');
    }

    /**
     * ルートキーの取得（URL用にuuidを使用）
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
