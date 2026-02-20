<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * UserCredential Model
 *
 * ユーザー認証情報を管理
 * 複数の認証プロバイダー（email, LINE, Google, Apple等）をサポート
 */
class UserCredential extends Model
{
    protected $table = 'user_credentials';

    /**
     * 認証プロバイダー定数
     */
    public const PROVIDER_EMAIL = 'email';
    public const PROVIDER_LINE = 'line';
    public const PROVIDER_GOOGLE = 'google';
    public const PROVIDER_APPLE = 'apple';
    public const PROVIDER_FACEBOOK = 'facebook';
    public const PROVIDER_MICROSOFT = 'microsoft';

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'email',
        'password',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'is_primary',
        'verified',
    ];

    protected $hidden = [
        'password',
        'access_token',
        'refresh_token',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'verified' => 'boolean',
        'token_expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns this credential
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is an email credential
     */
    public function isEmailCredential(): bool
    {
        return $this->provider === self::PROVIDER_EMAIL;
    }

    /**
     * Check if this is a LINE credential
     */
    public function isLineCredential(): bool
    {
        return $this->provider === self::PROVIDER_LINE;
    }

    /**
     * Check if the token is expired
     */
    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }

        return $this->token_expires_at->isPast();
    }

    /**
     * Scope for primary credentials
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope for verified credentials
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    /**
     * Scope for specific provider
     */
    public function scopeProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    /**
     * Find or create credential for OAuth provider
     *
     * @param string $provider プロバイダー名
     * @param string $providerId プロバイダーのユーザーID
     * @param int $userId ユーザーID
     * @param array $data 追加データ（email, access_token等）
     * @return self
     */
    public static function findOrCreateOAuth(string $provider, string $providerId, int $userId, array $data = []): self
    {
        $credential = self::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        if ($credential) {
            // 既存のクレデンシャルを更新
            if (!empty($data['access_token'])) {
                $credential->access_token = $data['access_token'];
            }
            if (!empty($data['refresh_token'])) {
                $credential->refresh_token = $data['refresh_token'];
            }
            if (!empty($data['token_expires_at'])) {
                $credential->token_expires_at = $data['token_expires_at'];
            }
            $credential->save();

            return $credential;
        }

        // 新規作成
        return self::create([
            'user_id' => $userId,
            'provider' => $provider,
            'provider_id' => $providerId,
            'email' => $data['email'] ?? null,
            'access_token' => $data['access_token'] ?? null,
            'refresh_token' => $data['refresh_token'] ?? null,
            'token_expires_at' => $data['token_expires_at'] ?? null,
            'is_primary' => $data['is_primary'] ?? false,
            'verified' => true,
        ]);
    }

    /**
     * Find user by OAuth provider
     *
     * @param string $provider プロバイダー名
     * @param string $providerId プロバイダーのユーザーID
     * @return User|null
     */
    public static function findUserByProvider(string $provider, string $providerId): ?User
    {
        $credential = self::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        return $credential?->user;
    }
}
