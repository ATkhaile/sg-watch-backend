<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Users\Entity\StatusEntity;
use App\Enums\StatusCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 会員自身の退会処理（自己削除）
 * 管理者によるユーザー削除（DeleteUsersUseCase）とは別
 */
final class WithdrawUseCase
{
    public function __invoke(): StatusEntity
    {
        $currentUser = auth()->user();

        if (!$currentUser) {
            return new StatusEntity(
                statusCode: StatusCode::UNAUTHORIZED,
                message: 'ログインが必要です'
            );
        }

        // システム管理者は退会不可
        if ($currentUser->is_system_admin) {
            return new StatusEntity(
                statusCode: StatusCode::FORBIDDEN,
                message: '管理者は退会できません'
            );
        }

        try {
            DB::beginTransaction();

            $user = User::find($currentUser->id);
            if (!$user) {
                DB::rollBack();
                return new StatusEntity(
                    statusCode: StatusCode::NOT_FOUND,
                    message: 'ユーザーが見つかりません'
                );
            }

            // ソフトデリート
            $user->delete();

            // JWTトークンを無効化
            auth()->logout();

            DB::commit();

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: '退会処理が完了しました'
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Withdraw failed: ' . $th->getMessage());
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: '退会処理に失敗しました'
            );
        }
    }
}
