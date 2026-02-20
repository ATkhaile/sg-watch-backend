<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stripe_sync_jobs', function (Blueprint $table) {
            // キュー管理用フィールド
            $table->timestamp('scheduled_at')->nullable()->after('job_type')->comment('実行予定日時');
            $table->string('cancelled_by')->nullable()->after('message')->comment('キャンセル実行者');
            $table->timestamp('cancelled_at')->nullable()->after('cancelled_by')->comment('キャンセル日時');

            // インデックス追加
            $table->index('scheduled_at');
            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stripe_sync_jobs', function (Blueprint $table) {
            $table->dropIndex(['scheduled_at']);
            $table->dropIndex(['status', 'scheduled_at']);
            $table->dropColumn(['scheduled_at', 'cancelled_by', 'cancelled_at']);
        });
    }
};
