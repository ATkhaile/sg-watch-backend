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
        // 凍結履歴テーブル
        Schema::create('user_suspension_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['suspend', 'unsuspend'])->comment('凍結/解除');
            $table->text('reason')->nullable()->comment('理由');
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null')->comment('操作した管理者');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // usersテーブルにis_suspendedカラムを追加
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('is_system_admin')->comment('凍結状態');
            $table->timestamp('suspended_at')->nullable()->after('is_suspended')->comment('最後に凍結された日時');
            $table->unsignedInteger('suspension_count')->default(0)->after('suspended_at')->comment('凍結された回数');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_suspended', 'suspended_at', 'suspension_count']);
        });

        Schema::dropIfExists('user_suspension_logs');
    }
};
