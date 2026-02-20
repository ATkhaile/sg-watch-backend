<?php

namespace Database\Seeders\ElcCommunity;

use App\Models\EntitlementType;
use Illuminate\Database\Seeder;

class EntitlementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * config/entitlements.phpからエンタイトルメントタイプを読み込んで登録
     *
     * デフォルトエンタイトルメント:
     * - admin_ui_access: 管理者画面のUIの表示
     * - paywall_disabled: 未加入時のPaywall非表示
     * - shop_access: ショップ機能利用
     * - profile_access: マイプロフィールの確認
     * - column_access_count: コラム開封権限（消費型）
     * - column_unlimited_access: コラム無限開封権
     * - ai_chat_monthly_limit: AIチャット月間上限
     * - ai_tokens: AIトークン（消費型）
     */
    public function run(): void
    {
        // configから定義を読み込む
        $entitlementTypes = config('entitlements.default_entitlements', []);

        if (empty($entitlementTypes)) {
            $this->command->warn('No entitlements defined in config/entitlements.php');
            return;
        }

        foreach ($entitlementTypes as $type) {
            EntitlementType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }

        $this->command->info('EntitlementTypeSeeder: Created/Updated ' . count($entitlementTypes) . ' entitlement types from config');
    }
}
