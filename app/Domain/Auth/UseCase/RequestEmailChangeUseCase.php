<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\StatusEntity;
use App\Enums\StatusCode;
use App\Exceptions\Domain\ErrorResourceException;
use App\Models\EmailChangeRequest;
use App\Models\User;
use App\Mail\VerifyEmailChange;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RequestEmailChangeUseCase
{
    public function __invoke(User $user, string $newEmail, string $password, string $verificationBaseUrl): StatusEntity
    {
        try {
            // パスワード確認
            if (!Hash::check($password, $user->password)) {
                throw new ErrorResourceException(message: 'パスワードが正しくありません');
            }

            // 既存の未使用リクエストを無効化
            EmailChangeRequest::where('user_id', $user->id)
                ->whereNull('verified_at')
                ->delete();

            // トークン生成
            $token = Str::random(64);
            $expiresAt = Carbon::now()->addMinutes(30);

            // リクエスト作成
            $request = EmailChangeRequest::create([
                'user_id' => $user->id,
                'current_email' => $user->email,
                'new_email' => $newEmail,
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            // 確認メールを新しいメールアドレスに送信
            $verificationUrl = rtrim($verificationBaseUrl, '/') . '?token=' . $token;

            Mail::to($newEmail)->send(new VerifyEmailChange([
                'name' => $user->full_name,
                'new_email' => $newEmail,
                'verification_url' => $verificationUrl,
            ]));

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: '確認メールを送信しました。新しいメールアドレスに届いたリンクをクリックして変更を完了してください。',
            );
        } catch (ErrorResourceException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ErrorResourceException(message: $e->getMessage());
        }
    }
}
