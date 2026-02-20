<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * UI表示設定を新しいDisplay Keys構造に移行
     */
    public function up(): void
    {
        // ========================================
        // 1. 旧テーブルを削除
        // ========================================
        Schema::dropIfExists('ui_visibility_settings');
        Schema::dropIfExists('ui_elements');

        // ========================================
        // 2. Display Key Versions テーブル（バージョン管理）
        // ========================================
        Schema::create('display_key_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version', 100)->unique()->comment('バージョン識別子');
            $table->string('package_version', 50)->nullable()->comment('パッケージバージョン');
            $table->integer('key_count')->default(0)->comment('登録キー数');
            $table->text('memo')->nullable()->comment('メモ');
            $table->string('operator')->nullable()->comment('操作者');
            $table->boolean('is_current')->default(false)->comment('現在使用中フラグ');
            $table->timestamp('registered_at')->useCurrent()->comment('登録日時');
            $table->timestamps();

            $table->index('is_current');
        });

        // ========================================
        // 3. Display Keys テーブル（表示キー）
        // ========================================
        Schema::create('display_keys', function (Blueprint $table) {
            $table->id();
            $table->string('version', 100)->comment('所属バージョン');
            $table->string('key', 200)->comment('キー識別子（例: member.feed）');
            $table->string('display_name')->comment('表示名');
            $table->text('description')->nullable()->comment('説明');
            $table->string('path', 500)->default('/')->comment('パス');
            $table->string('default_icon', 100)->default('FileIcon')->comment('デフォルトアイコン');
            $table->string('default_color', 50)->default('gray')->comment('デフォルトカラー');
            $table->enum('link_target', ['new_tab', 'same_tab', 'new_window'])->default('same_tab')->comment('リンクの開き方');
            $table->string('guard', 50)->default('member')->comment('ガード種別');
            $table->boolean('is_system')->default(true)->comment('システムキーフラグ');
            $table->timestamps();

            $table->unique(['version', 'key']);
            $table->index('version');
            $table->index('guard');

            $table->foreign('version')->references('version')->on('display_key_versions')->onDelete('cascade');
        });

        // ========================================
        // 4. Display Key Role Visibility テーブル（ロール別可視性）
        // ========================================
        Schema::create('display_key_role_visibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('display_key_id')->constrained('display_keys')->onDelete('cascade');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('cascade');
            $table->boolean('enabled')->default(false)->comment('表示有効フラグ');
            $table->timestamps();

            $table->unique(['display_key_id', 'role_id']);
        });

        // ========================================
        // 5. Layout Settings テーブル（レイアウト設定）
        // ========================================
        Schema::create('layout_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('cascade')->comment('ロールID（nullはシステム管理者用）');
            $table->string('tab', 50)->comment('タブ種別（member, admin）');
            $table->json('sections')->comment('セクション設定（JSON）');
            $table->timestamps();

            $table->unique(['role_id', 'tab']);
        });

        // ========================================
        // 6. Display Key Masters テーブル（マスタデータ）
        // ========================================
        Schema::create('display_key_masters', function (Blueprint $table) {
            $table->id();
            $table->string('version', 100)->comment('所属バージョン');
            $table->json('available_colors')->nullable()->comment('利用可能な色（JSON配列）');
            $table->json('guards')->nullable()->comment('ガード設定（JSON配列）');
            $table->timestamps();

            $table->unique('version');
            $table->foreign('version')->references('version')->on('display_key_versions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('display_key_masters');
        Schema::dropIfExists('layout_settings');
        Schema::dropIfExists('display_key_role_visibility');
        Schema::dropIfExists('display_keys');
        Schema::dropIfExists('display_key_versions');

        // 旧テーブルを復元
        Schema::create('ui_elements', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->string('key', 100);
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->string('category')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['type', 'key']);
        });

        Schema::create('ui_visibility_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ui_element_id')->constrained('ui_elements')->onDelete('cascade');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('cascade');
            $table->enum('visibility', ['public', 'development', 'hidden'])->default('hidden');
            $table->timestamps();
            $table->unique(['ui_element_id', 'role_id']);
        });
    }
};
