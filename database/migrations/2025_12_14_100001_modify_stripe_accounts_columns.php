<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * 外部キー制約を持つテーブルのリスト
     */
    private array $tablesWithForeignKeys = [
        'payment_plans' => 'payment_plans_stripe_account_id_foreign',
        'stripe_dashboard_stats' => 'stripe_dashboard_stats_stripe_account_id_foreign',
        'columns' => 'columns_stripe_account_id_foreign',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // カラムが既に存在するかチェックしてから追加
        $columns = Schema::getColumnListing('stripe_accounts');

        // Step 1: 新しいカラムを追加（存在しない場合のみ）
        Schema::table('stripe_accounts', function (Blueprint $table) use ($columns) {
            if (!in_array('account_type', $columns)) {
                $table->string('account_type', 20)->nullable()->after('display_name')->comment('Stripe Account type: standard/express/custom');
            }
            if (!in_array('parent_account_id', $columns)) {
                $table->unsignedBigInteger('parent_account_id')->nullable()->after('account_type')->comment('Parent stripe_accounts.id for Connect');
            }
            if (!in_array('object_type', $columns)) {
                $table->string('object_type', 50)->default('account')->after('parent_account_id')->comment('Stripe object type');
            }
            if (!in_array('email', $columns)) {
                $table->string('email')->nullable()->after('object_type')->comment('Stripe Account contact email');
            }
            if (!in_array('business_profile_product_description', $columns)) {
                $table->text('business_profile_product_description')->nullable()->after('business_type')->comment('Stripe business_profile.product_description');
            }
            if (!in_array('payout_settings', $columns)) {
                $table->json('payout_settings')->nullable()->after('currency')->comment('Stripe settings.payouts JSON');
            }
            if (!in_array('requirements_currently_due', $columns)) {
                $table->json('requirements_currently_due')->nullable()->after('payout_settings')->comment('Stripe requirements.currently_due');
            }
            if (!in_array('stripe_created', $columns)) {
                $table->timestamp('stripe_created')->nullable()->after('created_at')->comment('Stripe Account created timestamp');
            }
        });

        // Step 2: カラムのリネーム（存在する場合のみ）
        Schema::table('stripe_accounts', function (Blueprint $table) use ($columns) {
            if (in_array('account_id', $columns)) {
                $table->renameColumn('account_id', 'stripe_id');
            }
            if (in_array('account_name', $columns)) {
                $table->renameColumn('account_name', 'business_profile_name');
            }
            if (in_array('updator_id', $columns)) {
                $table->renameColumn('updator_id', 'updater_id');
            }
        });

        // Step 3: stripe_account_id → uuid にリネーム（存在する場合のみ）
        $columns = Schema::getColumnListing('stripe_accounts'); // リフレッシュ
        if (in_array('stripe_account_id', $columns)) {
            Schema::table('stripe_accounts', function (Blueprint $table) {
                $table->renameColumn('stripe_account_id', 'uuid');
            });
        }

        // Step 4: 新しいPK用のidカラムを追加（存在しない場合のみ）
        $columns = Schema::getColumnListing('stripe_accounts'); // リフレッシュ
        if (!in_array('id', $columns)) {
            // 外部キー制約を一時的に削除
            foreach ($this->tablesWithForeignKeys as $tableName => $constraintName) {
                if (Schema::hasTable($tableName)) {
                    try {
                        Schema::table($tableName, function (Blueprint $table) use ($constraintName) {
                            $table->dropForeign($constraintName);
                        });
                    } catch (\Exception $e) {
                        // 外部キーが存在しない場合は無視
                    }
                }
            }

            // 単一のALTER文で主キー変更と新カラム追加を同時に行う
            DB::statement('ALTER TABLE stripe_accounts ADD id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
            DB::statement('ALTER TABLE stripe_accounts ADD UNIQUE INDEX stripe_accounts_uuid_unique (uuid)');

            // 外部キー制約を再作成（uuidカラムを参照）
            foreach ($this->tablesWithForeignKeys as $tableName => $constraintName) {
                if (Schema::hasTable($tableName)) {
                    try {
                        Schema::table($tableName, function (Blueprint $table) use ($constraintName) {
                            $table->foreign('stripe_account_id', $constraintName)
                                ->references('uuid')
                                ->on('stripe_accounts')
                                ->onDelete('cascade');
                        });
                    } catch (\Exception $e) {
                        // 外部キーが既に存在する場合は無視
                    }
                }
            }
        }

        // Step 5: payment_linkカラムを削除（存在する場合のみ）
        $columns = Schema::getColumnListing('stripe_accounts'); // リフレッシュ
        if (in_array('payment_link', $columns)) {
            Schema::table('stripe_accounts', function (Blueprint $table) {
                $table->dropColumn('payment_link');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = Schema::getColumnListing('stripe_accounts');

        // payment_linkを復元
        if (!in_array('payment_link', $columns)) {
            Schema::table('stripe_accounts', function (Blueprint $table) {
                $table->string('payment_link')->nullable()->after('webhook_secret');
            });
        }

        // PKを元に戻す
        if (in_array('id', $columns)) {
            // 外部キー制約を一時的に削除（uuidを参照している）
            foreach ($this->tablesWithForeignKeys as $tableName => $constraintName) {
                if (Schema::hasTable($tableName)) {
                    try {
                        Schema::table($tableName, function (Blueprint $table) use ($constraintName) {
                            $table->dropForeign($constraintName);
                        });
                    } catch (\Exception $e) {
                        // 外部キーが存在しない場合は無視
                    }
                }
            }

            // uuid → stripe_account_id にリネーム
            if (in_array('uuid', $columns) && !in_array('stripe_account_id', $columns)) {
                Schema::table('stripe_accounts', function (Blueprint $table) {
                    $table->renameColumn('uuid', 'stripe_account_id');
                });
            }

            // 単一のALTER文でPK変更
            DB::statement('ALTER TABLE stripe_accounts DROP INDEX stripe_accounts_uuid_unique');
            DB::statement('ALTER TABLE stripe_accounts DROP PRIMARY KEY, DROP COLUMN id, ADD PRIMARY KEY (stripe_account_id)');

            // 外部キー制約を再作成（stripe_account_idカラムを参照）
            foreach ($this->tablesWithForeignKeys as $tableName => $constraintName) {
                if (Schema::hasTable($tableName)) {
                    try {
                        Schema::table($tableName, function (Blueprint $table) use ($constraintName) {
                            $table->foreign('stripe_account_id', $constraintName)
                                ->references('stripe_account_id')
                                ->on('stripe_accounts')
                                ->onDelete('cascade');
                        });
                    } catch (\Exception $e) {
                        // 外部キーが既に存在する場合は無視
                    }
                }
            }
        }

        // カラムのリネームを戻す
        $columns = Schema::getColumnListing('stripe_accounts');
        Schema::table('stripe_accounts', function (Blueprint $table) use ($columns) {
            if (in_array('stripe_id', $columns)) {
                $table->renameColumn('stripe_id', 'account_id');
            }
            if (in_array('business_profile_name', $columns)) {
                $table->renameColumn('business_profile_name', 'account_name');
            }
            if (in_array('updater_id', $columns)) {
                $table->renameColumn('updater_id', 'updator_id');
            }
        });

        // 追加したカラムを削除
        $columns = Schema::getColumnListing('stripe_accounts');
        $columnsToRemove = array_intersect([
            'account_type',
            'parent_account_id',
            'object_type',
            'email',
            'business_profile_product_description',
            'payout_settings',
            'requirements_currently_due',
            'stripe_created',
        ], $columns);

        if (!empty($columnsToRemove)) {
            Schema::table('stripe_accounts', function (Blueprint $table) use ($columnsToRemove) {
                $table->dropColumn($columnsToRemove);
            });
        }
    }
};
