<?php

namespace App\Domain\Google\Infrastructure;

use App\Domain\Google\Components\GoogleComponent;
use App\Domain\Google\Entity\GoogleCallbackRequestEntity;
use App\Domain\Google\Entity\GoogleAppLoginRequestEntity;
use App\Domain\Google\Repository\GoogleRepository;
use App\Domain\Sessions\Entity\CreateSessionRequestEntity;
use App\Domain\Sessions\Repository\SessionRepository;
use App\Models\User;
use App\Models\UserCredential;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use App\Components\PointBonusComponent;
use App\Models\ClientDomain;
use App\Models\SsoProvider;
use App\Enums\SsoProviderType;
use App\Enums\ClientType;
use App\Enums\Status;


class DbGoogleInfrastructure implements GoogleRepository
{
    private SessionRepository $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }


    public function login(GoogleCallbackRequestEntity $requestEntity): array
    {
        try {
            DB::beginTransaction();
            $clientDomain = ClientDomain::where([
                ['domain', request()->header('Origin')],
                ['status', Status::ACTIVE]
            ])->first();
            if (!$clientDomain) {
                throw new \Exception(__('google.callback.failed'));
            }
            $ssoProvider = SsoProvider::where([
                ['provider_type', SsoProviderType::GOOGLE],
                ['client_type', ClientType::WEB],
                ['status', Status::ACTIVE]
            ])->first();
            if (!$ssoProvider) {
                throw new \Exception(__('google.callback.failed'));
            }

            $tokenData = GoogleComponent::getAccessToken($requestEntity->code, $requestEntity->redirect_url, $ssoProvider);
            if (!$tokenData || !isset($tokenData['access_token'])) {
                throw new \Exception(__('google.callback.token_failed'));
            }
            $accessToken = $tokenData['access_token'];

            $userInfo = GoogleComponent::getUserInfo($accessToken);
            if (!$userInfo) {
                throw new \Exception(__('google.callback.user_info_failed'));
            }

            $user = $this->findOrCreateUser($requestEntity, $userInfo, $accessToken);
            if (!$user) {
                throw new \Exception(__('google.callback.failed'));
            }

            // 凍結チェック
            if ($user->is_suspended) {
                throw new \Exception('このアカウントは凍結されています。管理者にお問い合わせください。');
            }

            $token = auth()->login($user);
            if (!$token) {
                DB::rollBack();
                throw new \Exception(__('google.callback.failed'));
            }
            PointBonusComponent::dailyBonus();

            $sessionRequest = new CreateSessionRequestEntity(
                userId: $user->id,
                token: $token,
                ipAddress: request()->ip(),
                userAgent: request()->userAgent(),
                appId: request()->header('X-App-Id'),
                domain: request()->header('Origin')
            );
            if (!$this->sessionRepository->store($sessionRequest)) {
                DB::rollBack();
                throw new \Exception(__('google.callback.failed'));
            }

            DB::commit();

            return ['token' => $token];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function findOrCreateUser(GoogleCallbackRequestEntity $requestEntity, array $userInfo, ?string $accessToken = null): ?User
    {
        $email = $userInfo['email'];
        $googleId = $userInfo['id'] ?? $userInfo['sub'] ?? null;

        // 1. GoogleのプロバイダーIDで既存ユーザーを検索
        if ($googleId) {
            $existingUser = UserCredential::findUserByProvider(UserCredential::PROVIDER_GOOGLE, $googleId);
            if ($existingUser) {
                return $existingUser;
            }
        }

        // 2. メールで既存ユーザーを検索
        $user = User::where('email', $email)
            ->whereNull('deleted_at')
            ->first();

        if (!$user) {
            // 新規ユーザー作成
            $googleName = $userInfo['name'] ?? $email;
            $nameParts = explode(' ', $googleName, 2);

            $user = new User;
            $user->first_name = $nameParts[0];
            $user->last_name = $nameParts[1] ?? '';
            $user->email = $email;
            $user->email_verified_at = now();
            $user->is_system_admin = false;
            $user->password = '';
            $base = new BaseController();
            $user->invite_code = $base->generateUniqueInviteCode();

            if (!$user->save()) {
                return null;
            }

            // Assign user role
            $userRole = \App\Models\Role::where('name', 'user')->first();
            if ($userRole) {
                $user->roles()->syncWithoutDetaching([$userRole->id]);
            }
        }

        // 3. UserCredentialを作成/更新
        if ($googleId) {
            UserCredential::findOrCreateOAuth(
                UserCredential::PROVIDER_GOOGLE,
                $googleId,
                $user->id,
                [
                    'email' => $email,
                    'access_token' => $accessToken,
                    'is_primary' => !$user->credentials()->exists(),
                ]
            );
        }

        return $user;
    }

    public function appLogin(GoogleAppLoginRequestEntity $requestEntity): string
    {
        try {
            DB::beginTransaction();

            $userInfo = GoogleComponent::verifyAppToken($requestEntity->token);
            if (!$userInfo || empty($userInfo['email'])) {
                throw new \Exception(__('google.callback.user_info_failed'));
            }

            $user = $this->findOrCreateAppUser($requestEntity, $userInfo);
            if (!$user) {
                throw new \Exception(__('google.callback.failed'));
            }

            // 凍結チェック
            if ($user->is_suspended) {
                throw new \Exception('このアカウントは凍結されています。管理者にお問い合わせください。');
            }

            $token = auth()->login($user);
            if (!$token) {
                DB::rollBack();
                throw new \Exception(__('google.callback.failed'));
            }

            PointBonusComponent::dailyBonus();

            $sessionRequest = new CreateSessionRequestEntity(
                userId: $user->id,
                token: $token,
                ipAddress: request()->ip(),
                userAgent: request()->userAgent(),
                appId: request()->header('X-App-Id'),
                domain: request()->header('Origin')
            );
            if (!$this->sessionRepository->store($sessionRequest)) {
                DB::rollBack();
                throw new \Exception(__('google.callback.failed'));
            }

            DB::commit();

            return $token;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function findOrCreateAppUser(GoogleAppLoginRequestEntity $requestEntity, array $userInfo): ?User
    {
        $email = $userInfo['email'];
        $googleId = $userInfo['sub'] ?? $userInfo['id'] ?? null;

        // 1. GoogleのプロバイダーIDで既存ユーザーを検索
        if ($googleId) {
            $existingUser = UserCredential::findUserByProvider(UserCredential::PROVIDER_GOOGLE, $googleId);
            if ($existingUser) {
                return $existingUser;
            }
        }

        // 2. メールで既存ユーザーを検索
        $user = User::where('email', $email)
            ->whereNull('deleted_at')
            ->first();

        if (!$user) {
            // 新規ユーザー作成
            $googleName = $userInfo['name'] ?? $userInfo['email'];
            $nameParts = explode(' ', $googleName, 2);

            $user = new User;
            $user->first_name = $nameParts[0];
            $user->last_name = $nameParts[1] ?? '';
            $user->email = $email;
            $user->email_verified_at = now();
            $user->is_system_admin = false;
            $user->password = '';

            if (!$user->save()) {
                return null;
            }

            // Assign user role
            $userRole = \App\Models\Role::where('name', 'user')->first();
            if ($userRole) {
                $user->roles()->syncWithoutDetaching([$userRole->id]);
            }
        }

        // 3. UserCredentialを作成/更新
        if ($googleId) {
            UserCredential::findOrCreateOAuth(
                UserCredential::PROVIDER_GOOGLE,
                $googleId,
                $user->id,
                [
                    'email' => $email,
                    'is_primary' => !$user->credentials()->exists(),
                ]
            );
        }

        return $user;
    }
}
