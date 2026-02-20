<?php

namespace Database\Seeders\ElcCore;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期管理者アカウント
        $admin = User::firstOrCreate(
            ['email' => 'account+init@gameagelayer.com'],
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'email' => 'account+init@gameagelayer.com',
                'password' => Hash::make('Laravel@2025'),
                'is_system_admin' => true,
                'invite_code' => $this->generateUniqueInviteCode(),
            ]
        );

        // adminロールを付与
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // userロールを取得
        $userRole = $this->getUserRole();

        // テスト用一般ユーザーを作成
        $this->createTestUsers($userRole);
    }

    /**
     * userロールを取得
     * 注意: userロールの権限はPermissionSeederで設定されるため、
     * ここでは権限の上書きを行わない
     */
    private function getUserRole(): Role
    {
        // PermissionSeederで作成済みのuserロールを取得
        $userRole = Role::where('name', 'user')->first();

        if (!$userRole) {
            // フォールバック: PermissionSeederが実行されていない場合
            $userRole = Role::create([
                'name' => 'user',
                'display_name' => 'ユーザー',
                'description' => '一般ユーザー向けのデフォルトロール',
                'is_system' => true,
            ]);
        }

        return $userRole;
    }

    /**
     * テスト用一般ユーザーを作成
     */
    private function createTestUsers(Role $userRole): void
    {
        $testUsers = [
            [
                'first_name' => 'Test',
                'last_name' => 'User1',
                'email' => 'account+general1@gameagelayer.com',
            ],
            [
                'first_name' => 'Test',
                'last_name' => 'User2',
                'email' => 'account+general2@gameagelayer.com',
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('Laravel@2025'),
                    'is_system_admin' => false,
                    'invite_code' => $this->generateUniqueInviteCode(),
                ]
            );

            // userロールを付与
            $user->roles()->syncWithoutDetaching([$userRole->id]);
        }
    }

    /**
     * ユニークな招待コードを生成
     */
    private function generateUniqueInviteCode(): string
    {
        do {
            $code = Str::upper(Str::random(6)) . '-' . Str::upper(Str::random(6));
        } while (DB::table('users')->where('invite_code', $code)->exists());

        return $code;
    }
}
