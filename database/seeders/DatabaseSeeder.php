<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. 都道府県マスターを作成 (Address用)
        $this->call(\Database\Seeders\ElcCore\PrefectureSeeder::class);

        // 2. Permission と admin/user ロールを作成 (Auth/RBAC用)
        $this->call(\Database\Seeders\ElcCore\PermissionSeeder::class);

        // 3. 初期ユーザーを作成し、admin ロールを付与 (Auth用)
        $this->call(\Database\Seeders\ElcCore\UserSeeder::class);

        // 4. エンタイトルメントタイプの初期データを作成 (Stripe決済用)
        $this->call(\Database\Seeders\ElcCommunity\EntitlementTypeSeeder::class);

        // 5. メンバーシップアクション設定の初期データを作成 (決済用)
        $this->call(\Database\Seeders\ElcCore\MembershipActionSettingsSeeder::class);

        // 6. Shop: brands, categories, products mẫu
        $this->call(\Database\Seeders\Shop\ShopSeeder::class);
    }
}
