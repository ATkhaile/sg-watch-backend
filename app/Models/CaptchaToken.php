<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CaptchaToken extends Model
{
    use HasFactory;

    protected $table = 'captcha_tokens';

    protected $fillable = [
        'token',
        'challenge',
        'ip_address',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * 有効期限（分）
     */
    public const EXPIRES_MINUTES = 10;

    /**
     * チャレンジ文字列の長さ
     */
    public const CHALLENGE_LENGTH = 6;

    /**
     * 新しいCaptchaトークンを生成
     */
    public static function generate(?string $ipAddress = null): self
    {
        // 期限切れトークンを削除
        self::where('expires_at', '<', now())->delete();

        return self::create([
            'token' => Str::random(64),
            'challenge' => self::generateChallenge(),
            'ip_address' => $ipAddress,
            'expires_at' => now()->addMinutes(self::EXPIRES_MINUTES),
            'used' => false,
        ]);
    }

    /**
     * チャレンジ文字列を生成（数字とアルファベット）
     */
    private static function generateChallenge(): string
    {
        // 紛らわしい文字を除外 (0, O, 1, l, I)
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $challenge = '';

        for ($i = 0; $i < self::CHALLENGE_LENGTH; $i++) {
            $challenge .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $challenge;
    }

    /**
     * トークンを検証
     */
    public static function verify(string $token, string $challenge, ?string $ipAddress = null): bool
    {
        $captcha = self::where('token', $token)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$captcha) {
            return false;
        }

        // チャレンジ文字列を大文字小文字区別なく比較
        if (strtoupper($captcha->challenge) !== strtoupper($challenge)) {
            return false;
        }

        // IPアドレスチェック（オプション）
        if ($ipAddress && $captcha->ip_address && $captcha->ip_address !== $ipAddress) {
            return false;
        }

        // 使用済みにマーク
        $captcha->update(['used' => true]);

        return true;
    }

    /**
     * 期限切れかどうか
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
