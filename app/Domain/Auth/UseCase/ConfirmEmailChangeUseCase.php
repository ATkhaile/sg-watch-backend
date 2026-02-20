<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\StatusEntity;
use App\Enums\StatusCode;
use App\Exceptions\Domain\ErrorResourceException;
use App\Models\EmailChangeRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConfirmEmailChangeUseCase
{
    public function __invoke(string $token): StatusEntity
    {
        try {
            $request = EmailChangeRequest::where('token', $token)->first();

            if (!$request) {
                throw new ErrorResourceException(message: '無効なトークンです');
            }

            if ($request->isExpired()) {
                throw new ErrorResourceException(message: 'リンクの有効期限が切れています。再度メールアドレス変更をお試しください');
            }

            if ($request->isVerified()) {
                throw new ErrorResourceException(message: 'このリンクは既に使用されています');
            }

            // 新しいメールアドレスが既に使用されていないか最終確認
            $existingUser = User::where('email', $request->new_email)
                ->where('id', '!=', $request->user_id)
                ->first();

            if ($existingUser) {
                throw new ErrorResourceException(message: 'このメールアドレスは既に使用されています');
            }

            DB::transaction(function () use ($request) {
                // ユーザーのメールアドレスを更新
                $user = User::find($request->user_id);
                $user->email = $request->new_email;
                $user->save();

                // リクエストを確認済みにする
                $request->verified_at = Carbon::now();
                $request->save();
            });

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: 'メールアドレスを変更しました',
            );
        } catch (ErrorResourceException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ErrorResourceException(message: $e->getMessage());
        }
    }
}
