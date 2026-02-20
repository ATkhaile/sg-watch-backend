<?php

namespace Database\Seeders\ElcCore;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * userロールから完全に除外するUseCase Group
     * これらのグループの権限は一切userに付与されない
     */
    private const USER_EXCLUDED_GROUPS = [
        // 管理者専用
        'Authorization',       // ロール・権限管理
        'Users',               // ユーザー管理
        'Maintenance',         // メンテナンス
        'SystemSetting',       // システム設定
        'AppSetting',          // アプリ設定
        'AppRelease',          // アプリリリース
        'AppPage',             // アプリページ
        'DatabaseLog',         // データベースログ
        // 通知系
        'Notifications',       // 通知管理
        'Firebase',            // Firebase通知
        'PusherNotification',  // Pusher通知
        'NotificationPush',    // プッシュ通知
        'FcmToken',            // FCM通知
        // AI/連携系
        'MySnsBot',            // SNSボット
        'Dify',                // Dify連携
        'AiModelPrice',        // AIモデル価格
        'AiProvider',          // AIプロバイダー
        // スケジュール/場所/サービス系
        'UserSchedule',        // ユーザースケジュール
        'Places',              // 場所管理
        'Services',            // サービス管理
        'Schedules',           // スケジュール管理
        'ScheduleTags',        // スケジュールタグ管理
        // ランク・マスター
        'RankMaster',          // ランクマスター
        // EC/商品
        'AishipProduct',       // Aiship管理
        // 決済関連
        'StripeTransaction',   // Stripe取引
        // 予約サービス関連 (管理者専用)
        'Location',
        'GolphLocation',
        'SaunaLocation',
        'Coupon',
        'Term',
        'GolphPlan',
        'AvailabilitySlot',
    ];

    /**
     * userロールで「表示（list/find/view/get/detail）のみ許可」するUseCase Group
     * それ以外の操作（create/update/delete）は除外
     */
    private const USER_VIEW_ONLY_GROUPS = [
        'Category',      // カテゴリ管理の表示のみ
        'Tags',          // タグ管理の表示のみ
        'News',          // ニュース管理の表示のみ
        'StripeAccount', // Stripeアカウントの表示のみ
        'PaymentPlans',  // 決済プランの表示のみ
    ];

    /**
     * userロールで許可する特定の権限（DailyBonusの表示/更新のみ）
     */
    private const USER_ALLOWED_SPECIFIC_PERMISSIONS = [
        'get-daily-bonus',    // デイリーボーナス表示
        'create-daily-bonus', // デイリーボーナス更新（設定）
    ];

    /**
     * Run the database seeds.
     * config/permissions.php から権限を読み込んでシードする
     * admin ロールと user ロールを作成し、権限を紐付ける
     *
     * @return void
     */
    public function run()
    {
        $permissionsConfig = config('permissions');
        $allPermissionIds = [];
        $userPermissionIds = [];

        // 全Permissionを作成
        foreach ($permissionsConfig as $usecaseGroup => $permissions) {
            foreach ($permissions as $permission) {
                $created = Permission::firstOrCreate(
                    ['name' => $permission['name']],
                    [
                        'name' => $permission['name'],
                        'display_name' => $permission['display_name'],
                        'description' => $permission['description'],
                        'usecase_group' => $usecaseGroup,
                    ]
                );
                $allPermissionIds[] = $created->id;

                // userロール用の権限判定
                if ($this->isUserPermission($usecaseGroup, $permission['name'])) {
                    $userPermissionIds[] = $created->id;
                }
            }
        }

        // admin ロールを作成（システムロール）
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'name' => 'admin',
                'display_name' => '管理者',
                'description' => '全ての権限を持つ管理者ロール',
                'is_system' => true,
            ]
        );
        $adminRole->update(['is_system' => true]);

        // 全Permissionをadminロールに紐付け
        $adminRole->permissions()->syncWithoutDetaching($allPermissionIds);

        // user ロールを作成（システムロール）
        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'name' => 'user',
                'display_name' => 'ユーザー',
                'description' => '一般ユーザー向けのデフォルトロール',
                'is_system' => true,
            ]
        );
        $userRole->update(['is_system' => true]);

        // user用の権限を紐付け
        $userRole->permissions()->syncWithoutDetaching($userPermissionIds);
    }

    /**
     * 指定された権限がuserロールに含まれるかを判定
     *
     * @param string $usecaseGroup
     * @param string $permissionName
     * @return bool
     */
    private function isUserPermission(string $usecaseGroup, string $permissionName): bool
    {
        // 完全除外グループの場合は除外
        if (in_array($usecaseGroup, self::USER_EXCLUDED_GROUPS, true)) {
            return false;
        }

        // DailyBonusは特定の権限のみ許可
        if ($usecaseGroup === 'DailyBonus') {
            return in_array($permissionName, self::USER_ALLOWED_SPECIFIC_PERMISSIONS, true);
        }

        // 表示のみ許可のグループ
        if (in_array($usecaseGroup, self::USER_VIEW_ONLY_GROUPS, true)) {
            return $this->isViewPermission($permissionName);
        }

        // それ以外のグループは全ての権限を許可
        return true;
    }

    /**
     * 権限名が「表示系」かどうかを判定
     * list, find, view, get, detail, history などのプレフィックスを持つ権限は表示系
     *
     * @param string $permissionName
     * @return bool
     */
    private function isViewPermission(string $permissionName): bool
    {
        $viewPrefixes = [
            'list-',
            'find-',
            'view-',
            'get-',
            'detail-',
            'history-',
        ];

        foreach ($viewPrefixes as $prefix) {
            if (str_starts_with($permissionName, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
