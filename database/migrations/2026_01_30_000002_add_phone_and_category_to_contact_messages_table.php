<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('reply_email')->comment('電話番号');
            $table->string('category', 50)->default('general')->after('organization')
                ->comment('お問い合わせ種別: general, support, sales, partnership, other');
        });

        // organization カラムを nullable に変更（company として使用するため）
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('organization', 100)->nullable()->change();
        });

        // privacy_agreed カラムを削除（不要になった）
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn('privacy_agreed');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->boolean('privacy_agreed')->default(true)->after('user_id');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('organization', 100)->nullable(false)->change();
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['phone', 'category']);
        });
    }
};
