<?php

use App\Enums\ReportStatus;
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
        Schema::create('community_post_reports', function (Blueprint $table) {
            $table->id();

            // 報告対象（投稿またはコメント）
            $table->unsignedBigInteger('community_post_id')->nullable();
            $table->unsignedBigInteger('community_post_comment_id')->nullable();

            // 報告者情報
            $table->unsignedBigInteger('reporter_id');

            // 報告内容
            $table->text('reason');

            // 管理用フィールド
            $table->enum('status', ReportStatus::getValues())->default(ReportStatus::PENDING);
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // 外部キー制約
            $table->foreign('community_post_id')
                ->references('id')
                ->on('community_posts')
                ->onDelete('cascade');

            $table->foreign('community_post_comment_id')
                ->references('id')
                ->on('community_post_comments')
                ->onDelete('cascade');

            $table->foreign('reporter_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('reviewed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            // インデックス
            $table->index(['status', 'created_at']);
            $table->index('reporter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_post_reports');
    }
};
