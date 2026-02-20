<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ロールベースのコンテンツCRUD権限管理（ポリモーフィック）
     * Columns, Series, その他コンテンツに対してロールごとのCRUD権限を設定
     */
    public function up(): void
    {
        Schema::create('role_content_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->comment('ロールID');

            // morphs()の代わりに手動でカラムを定義してインデックス名を制御
            $table->string('permissionable_type');
            $table->unsignedBigInteger('permissionable_id');

            $table->boolean('can_create')->default(false)->comment('作成権限');
            $table->boolean('can_read')->default(false)->comment('読取権限');
            $table->boolean('can_update')->default(false)->comment('更新権限');
            $table->boolean('can_delete')->default(false)->comment('削除権限');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            // 同一ロール・同一コンテンツに対して重複しないように
            $table->unique(['role_id', 'permissionable_id', 'permissionable_type'], 'role_content_unique');

            // ポリモーフィックリレーション用インデックス（名前を明示的に指定）
            $table->index(['permissionable_type', 'permissionable_id'], 'rcp_permissionable_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_content_permissions');
    }
};
