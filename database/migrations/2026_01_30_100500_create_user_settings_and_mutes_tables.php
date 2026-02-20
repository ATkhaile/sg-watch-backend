<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('user_community_settings', function (Blueprint $table) {
      $table->foreignId('user_id')->primary()->constrained('users')->cascadeOnDelete();
      $table->enum('send_method', ['enter', 'shift_enter'])->default('shift_enter');
      $table->boolean('is_private')->default(false);
      $table->boolean('is_location_visible')->default(true);
      $table->boolean('notify_new_post')->default(true);
      $table->boolean('notify_group_post')->default(true);
      $table->boolean('notify_comment')->default(true);
      $table->boolean('notify_mention')->default(true);
      $table->boolean('notify_reaction')->default(false);
      $table->boolean('notify_follow_request')->default(false);
      $table->timestamps();
    });

    // Backfill settings for existing users
    $now = now();
    DB::statement("
        INSERT INTO user_community_settings (user_id, created_at, updated_at)
        SELECT id, '$now', '$now' FROM users
    ");

    Schema::create('user_mutes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('muter_id')->constrained('users')->cascadeOnDelete();
      $table->foreignId('muted_id')->constrained('users')->cascadeOnDelete();
      $table->timestamps();

      $table->unique(['muter_id', 'muted_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_mutes');
    Schema::dropIfExists('user_community_settings');
  }
};
