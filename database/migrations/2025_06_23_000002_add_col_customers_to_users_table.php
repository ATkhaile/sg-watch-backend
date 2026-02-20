<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'plan_id')) {
                $table->ulid('plan_id')->nullable()->comment('契約プランID');
            }
            if (!Schema::hasColumn('users', 'sns_limits')) {
                $table->json('sns_limits')->nullable();
            }
            if (!Schema::hasColumn('users', 'sns_developer')) {
                $table->json('sns_developer')->nullable();
            }
            if (!Schema::hasColumn('users', 'trial')) {
                $table->boolean('trial')->default(true);
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->tinyInteger('status')->default(1)->comment('ステータス enumで管理想定');
            }
            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable()->comment('会社名');
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->comment('電話番号');
            }
            if (!Schema::hasColumn('users', 'postcode')) {
                $table->string('postcode')->nullable()->comment('郵便番号');
            }
            if (!Schema::hasColumn('users', 'prefecture_id')) {
                $table->ulid('prefecture_id')->nullable()->comment('都道府県ID');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->comment('市区町村');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->comment('番地以降');
            }
            if (!Schema::hasColumn('users', 'description')) {
                $table->text('description')->nullable()->comment('自己紹介');
            }
            if (!Schema::hasColumn('users', 'image')) {
                $table->text('image')->nullable()->comment('アバター');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->tinyInteger('gender')->default(3)->comment('性別: なし０，男性１，女性２，どちらでもない３');
            }
            if (!Schema::hasColumn('users', 'birthday')) {
                $table->date('birthday')->nullable()->comment('誕生日');
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->datetime('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(false);
            }
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedInteger('role_id')->default(1);
            }
            if (!Schema::hasColumn('users', 'change_email')) {
                $table->string('change_email')->nullable();
            }
            if (!Schema::hasColumn('users', 'change_email_token')) {
                $table->string('change_email_token')->nullable();
            }
            if (!Schema::hasColumn('users', 'change_email_token_expire')) {
                $table->datetime('change_email_token_expire')->nullable();
            }
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string("referral_code")->nullable()->comment('紹介コード');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'plan_id') && Schema::hasTable('plans')) {
                try {
                    $table->foreign('plan_id')->references('plan_id')->on('plans');
                } catch (\Exception $e) {
                }
            }
            if (Schema::hasColumn('users', 'prefecture_id') && Schema::hasTable('prefectures')) {
                try {
                    $table->foreign('prefecture_id')->references('prefecture_id')->on('prefectures');
                } catch (\Exception $e) {
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropForeign(['plan_id']);
            } catch (\Exception $e) {
            }
            try {
                $table->dropForeign(['prefecture_id']);
            } catch (\Exception $e) {
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columnsToCheck = [
                'plan_id',
                'sns_limits',
                'sns_developer',
                'trial',
                'status',
                'company_name',
                'phone_number',
                'postcode',
                'prefecture_id',
                'city',
                'address',
                'description',
                'image',
                'gender',
                'birthday',
                'email_verified_at',
                'active',
                'role_id',
                'change_email',
                'change_email_token',
                'change_email_token_expire',
                'referral_code',
                'deleted_at'
            ];

            $columnsToRemove = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $columnsToRemove[] = $column;
                }
            }

            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
    }
};
