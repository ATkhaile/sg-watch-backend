<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 残りの拡張Stripeテーブル
     */
    public function up(): void
    {
        // 1. stripe_tax_rates - 税率
        if (!Schema::hasTable('stripe_tax_rates')) {
            Schema::create('stripe_tax_rates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Tax Rate ID (txr_xxx)');
                $table->string('display_name')->comment('表示名');
                $table->string('description')->nullable()->comment('説明');
                $table->string('jurisdiction', 100)->nullable()->comment('管轄区域');
                $table->decimal('percentage', 5, 4)->comment('税率(%)');
                $table->boolean('inclusive')->default(false)->comment('税込み');
                $table->boolean('active')->default(true)->comment('有効フラグ');
                $table->string('country', 10)->nullable()->comment('国');
                $table->string('state', 100)->nullable()->comment('州/地域');
                $table->string('tax_type', 50)->nullable()->comment('税タイプ');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'active']);
            });
        }

        // 2. stripe_tax_codes - 税コード
        if (!Schema::hasTable('stripe_tax_codes')) {
            Schema::create('stripe_tax_codes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Tax Code ID (txcd_xxx)');
                $table->string('name')->comment('名前');
                $table->text('description')->nullable()->comment('説明');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
            });
        }

        // 3. stripe_shipping_rates - 送料レート
        if (!Schema::hasTable('stripe_shipping_rates')) {
            Schema::create('stripe_shipping_rates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Shipping Rate ID (shr_xxx)');
                $table->string('display_name')->comment('表示名');
                $table->string('type', 50)->comment('タイプ (fixed_amount)');
                $table->bigInteger('fixed_amount')->nullable()->comment('固定金額');
                $table->string('currency', 10)->nullable()->comment('通貨');
                $table->json('delivery_estimate')->nullable()->comment('配送予定');
                $table->boolean('active')->default(true)->comment('有効フラグ');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'active']);
            });
        }

        // 4. stripe_tokens - トークン
        if (!Schema::hasTable('stripe_tokens')) {
            Schema::create('stripe_tokens', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Token ID (tok_xxx)');
                $table->string('type', 50)->comment('タイプ (card, bank_account, etc.)');
                $table->string('card_last4', 4)->nullable()->comment('カード下4桁');
                $table->string('card_brand', 50)->nullable()->comment('カードブランド');
                $table->boolean('used')->default(false)->comment('使用済み');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
            });
        }

        // 5. stripe_sources - ソース
        if (!Schema::hasTable('stripe_sources')) {
            Schema::create('stripe_sources', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Source ID (src_xxx)');
                $table->string('type', 50)->comment('タイプ');
                $table->bigInteger('amount')->nullable()->comment('金額');
                $table->string('currency', 10)->nullable()->comment('通貨');
                $table->string('owner_name')->nullable()->comment('オーナー名');
                $table->string('owner_email')->nullable()->comment('オーナーメール');
                $table->string('status', 50)->comment('ステータス');
                $table->string('customer_id', 100)->nullable()->comment('顧客ID');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'customer_id']);
            });
        }

        // 6. stripe_mandates - マンダート
        if (!Schema::hasTable('stripe_mandates')) {
            Schema::create('stripe_mandates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Mandate ID (mandate_xxx)');
                $table->string('customer_id', 100)->nullable()->comment('顧客ID');
                $table->string('payment_method_id', 100)->nullable()->comment('支払い方法ID');
                $table->string('type', 50)->comment('タイプ (multi_use, single_use)');
                $table->string('status', 50)->comment('ステータス');
                $table->timestamp('accepted_at')->nullable()->comment('承諾日時');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'customer_id']);
                $table->index(['stripe_account_id', 'payment_method_id'], 'mandates_pm_idx');
            });
        }

        // 7. stripe_files - ファイル
        if (!Schema::hasTable('stripe_files')) {
            Schema::create('stripe_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe File ID (file_xxx)');
                $table->string('purpose', 100)->comment('用途');
                $table->string('filename')->nullable()->comment('ファイル名');
                $table->bigInteger('size')->nullable()->comment('サイズ（バイト）');
                $table->string('file_type', 50)->nullable()->comment('ファイルタイプ');
                $table->text('url')->nullable()->comment('URL');
                $table->timestamp('expires_at')->nullable()->comment('有効期限');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'purpose']);
            });
        }

        // 8. stripe_account_links - アカウントリンク
        if (!Schema::hasTable('stripe_account_links')) {
            Schema::create('stripe_account_links', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Account Link ID');
                $table->string('connected_account_id', 100)->comment('接続先アカウントID');
                $table->string('type', 50)->comment('タイプ');
                $table->text('url')->nullable()->comment('URL');
                $table->timestamp('expires_at')->nullable()->comment('有効期限');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
            });
        }

        // 9. stripe_persons - 個人情報
        if (!Schema::hasTable('stripe_persons')) {
            Schema::create('stripe_persons', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Person ID (person_xxx)');
                $table->string('connected_account_id', 100)->comment('接続先アカウントID');
                $table->string('first_name')->nullable()->comment('名');
                $table->string('last_name')->nullable()->comment('姓');
                $table->string('relationship_title')->nullable()->comment('役職');
                $table->string('email')->nullable()->comment('メール');
                $table->string('phone', 50)->nullable()->comment('電話');
                $table->boolean('id_number_provided')->default(false)->comment('ID番号提供済み');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'connected_account_id'], 'persons_conn_acct_idx');
            });
        }

        // 10. stripe_capabilities - 機能
        if (!Schema::hasTable('stripe_capabilities')) {
            Schema::create('stripe_capabilities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Capability ID');
                $table->string('connected_account_id', 100)->comment('接続先アカウントID');
                $table->string('capability_name', 100)->comment('機能名');
                $table->string('status', 50)->comment('ステータス');
                $table->boolean('requested')->default(false)->comment('リクエスト済み');
                $table->timestamp('requested_at')->nullable()->comment('リクエスト日時');
                $table->text('disabled_reason')->nullable()->comment('無効理由');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'connected_account_id'], 'capabilities_conn_acct_idx');
            });
        }

        // 11. stripe_country_specs - 国別仕様
        if (!Schema::hasTable('stripe_country_specs')) {
            Schema::create('stripe_country_specs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('country', 10)->comment('国コード');
                $table->string('default_currency', 10)->nullable()->comment('デフォルト通貨');
                $table->json('supported_payment_currencies')->nullable()->comment('サポート通貨');
                $table->json('supported_transfer_countries')->nullable()->comment('サポート送金先国');
                $table->json('verification_fields')->nullable()->comment('確認フィールド');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'country']);
            });
        }

        // 12. stripe_issuing_cardholders - カードホルダー
        if (!Schema::hasTable('stripe_issuing_cardholders')) {
            Schema::create('stripe_issuing_cardholders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Cardholder ID (ich_xxx)');
                $table->string('name')->comment('名前');
                $table->string('email')->nullable()->comment('メール');
                $table->string('phone', 50)->nullable()->comment('電話');
                $table->string('status', 50)->comment('ステータス');
                $table->string('type', 50)->comment('タイプ (individual, company)');
                $table->json('billing')->nullable()->comment('請求先情報');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
            });
        }

        // 13. stripe_issuing_cards - カード
        if (!Schema::hasTable('stripe_issuing_cards')) {
            Schema::create('stripe_issuing_cards', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Card ID (ic_xxx)');
                $table->string('cardholder_id', 100)->comment('カードホルダーID');
                $table->string('last4', 4)->comment('下4桁');
                $table->string('brand', 50)->comment('ブランド');
                $table->integer('exp_month')->comment('有効期限月');
                $table->integer('exp_year')->comment('有効期限年');
                $table->string('status', 50)->comment('ステータス');
                $table->string('type', 50)->comment('タイプ (physical, virtual)');
                $table->string('currency', 10)->comment('通貨');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'cardholder_id'], 'issuing_cards_holder_idx');
            });
        }

        // 14. stripe_issuing_authorizations - 発行オーソリ
        if (!Schema::hasTable('stripe_issuing_authorizations')) {
            Schema::create('stripe_issuing_authorizations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Authorization ID (iauth_xxx)');
                $table->string('card_id', 100)->comment('カードID');
                $table->string('cardholder_id', 100)->nullable()->comment('カードホルダーID');
                $table->bigInteger('amount')->comment('金額');
                $table->string('currency', 10)->comment('通貨');
                $table->string('status', 50)->comment('ステータス');
                $table->boolean('approved')->default(false)->comment('承認済み');
                $table->json('merchant_data')->nullable()->comment('加盟店データ');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'card_id'], 'issuing_auth_card_idx');
            });
        }

        // 15. stripe_issuing_transactions - 発行取引
        if (!Schema::hasTable('stripe_issuing_transactions')) {
            Schema::create('stripe_issuing_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Transaction ID (ipi_xxx)');
                $table->string('card_id', 100)->comment('カードID');
                $table->string('cardholder_id', 100)->nullable()->comment('カードホルダーID');
                $table->string('authorization_id', 100)->nullable()->comment('オーソリID');
                $table->bigInteger('amount')->comment('金額');
                $table->string('currency', 10)->comment('通貨');
                $table->string('type', 50)->comment('タイプ');
                $table->json('merchant_data')->nullable()->comment('加盟店データ');
                $table->string('dispute_id', 100)->nullable()->comment('異議ID');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'card_id'], 'issuing_tx_card_idx');
            });
        }

        // 16. stripe_issuing_disputes - 発行異議
        if (!Schema::hasTable('stripe_issuing_disputes')) {
            Schema::create('stripe_issuing_disputes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Dispute ID (idp_xxx)');
                $table->string('transaction_id', 100)->comment('取引ID');
                $table->bigInteger('amount')->comment('金額');
                $table->string('currency', 10)->comment('通貨');
                $table->string('reason', 100)->comment('理由');
                $table->string('status', 50)->comment('ステータス');
                $table->json('evidence')->nullable()->comment('証拠');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'transaction_id'], 'issuing_dispute_tx_idx');
            });
        }

        // 17. stripe_terminal_locations - 端末ロケーション
        if (!Schema::hasTable('stripe_terminal_locations')) {
            Schema::create('stripe_terminal_locations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Location ID (tml_xxx)');
                $table->string('display_name')->comment('表示名');
                $table->string('address_line1')->nullable()->comment('住所1');
                $table->string('address_line2')->nullable()->comment('住所2');
                $table->string('city')->nullable()->comment('市区町村');
                $table->string('state')->nullable()->comment('都道府県');
                $table->string('country', 10)->nullable()->comment('国');
                $table->string('postal_code', 20)->nullable()->comment('郵便番号');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
            });
        }

        // 18. stripe_terminal_readers - 端末リーダー
        if (!Schema::hasTable('stripe_terminal_readers')) {
            Schema::create('stripe_terminal_readers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Reader ID (tmr_xxx)');
                $table->string('label')->nullable()->comment('ラベル');
                $table->string('location_id', 100)->nullable()->comment('ロケーションID');
                $table->string('device_type', 100)->comment('デバイスタイプ');
                $table->string('status', 50)->nullable()->comment('ステータス');
                $table->string('serial_number', 100)->nullable()->comment('シリアル番号');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'location_id'], 'terminal_readers_loc_idx');
            });
        }

        // 19. stripe_verification_sessions - 本人確認セッション
        if (!Schema::hasTable('stripe_verification_sessions')) {
            Schema::create('stripe_verification_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Verification Session ID (vs_xxx)');
                $table->string('status', 50)->comment('ステータス');
                $table->string('type', 50)->comment('タイプ');
                $table->string('client_secret')->nullable()->comment('クライアントシークレット');
                $table->json('options')->nullable()->comment('オプション');
                $table->json('verified_outputs')->nullable()->comment('確認済み出力');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'status']);
            });
        }

        // 20. stripe_radar_value_lists - Radarバリューリスト
        if (!Schema::hasTable('stripe_radar_value_lists')) {
            Schema::create('stripe_radar_value_lists', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Value List ID (rsl_xxx)');
                $table->string('name')->comment('名前');
                $table->string('alias')->comment('エイリアス');
                $table->string('item_type', 50)->comment('アイテムタイプ');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
            });
        }

        // 21. stripe_radar_value_list_items - Radarバリューリスト項目
        if (!Schema::hasTable('stripe_radar_value_list_items')) {
            Schema::create('stripe_radar_value_list_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Value List Item ID (rsli_xxx)');
                $table->string('value_list_id', 100)->comment('バリューリストID');
                $table->string('value')->comment('値');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id']);
                $table->index(['stripe_account_id', 'value_list_id'], 'radar_vli_list_idx');
            });
        }

        // subscription_schedulesテーブルにインデックスが足りていれば追加
        if (Schema::hasTable('stripe_subscription_schedules')) {
            // インデックスが存在しないかチェックして追加
            try {
                Schema::table('stripe_subscription_schedules', function (Blueprint $table) {
                    $table->index(['stripe_account_id', 'customer_id'], 'sub_scheds_acct_cust_idx');
                });
            } catch (\Exception $e) {
                // インデックスが既に存在する場合は無視
            }
            try {
                Schema::table('stripe_subscription_schedules', function (Blueprint $table) {
                    $table->index(['stripe_account_id', 'status'], 'sub_scheds_acct_status_idx');
                });
            } catch (\Exception $e) {
                // インデックスが既に存在する場合は無視
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_radar_value_list_items');
        Schema::dropIfExists('stripe_radar_value_lists');
        Schema::dropIfExists('stripe_verification_sessions');
        Schema::dropIfExists('stripe_terminal_readers');
        Schema::dropIfExists('stripe_terminal_locations');
        Schema::dropIfExists('stripe_issuing_disputes');
        Schema::dropIfExists('stripe_issuing_transactions');
        Schema::dropIfExists('stripe_issuing_authorizations');
        Schema::dropIfExists('stripe_issuing_cards');
        Schema::dropIfExists('stripe_issuing_cardholders');
        Schema::dropIfExists('stripe_country_specs');
        Schema::dropIfExists('stripe_capabilities');
        Schema::dropIfExists('stripe_persons');
        Schema::dropIfExists('stripe_account_links');
        Schema::dropIfExists('stripe_files');
        Schema::dropIfExists('stripe_mandates');
        Schema::dropIfExists('stripe_sources');
        Schema::dropIfExists('stripe_tokens');
        Schema::dropIfExists('stripe_shipping_rates');
        Schema::dropIfExists('stripe_tax_codes');
        Schema::dropIfExists('stripe_tax_rates');
    }
};
