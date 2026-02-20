<?php

use App\Enums\CanSeeCommentType;
use App\Enums\CanSeePostType;
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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'read_privacy_terms')) {
                $table->boolean('read_privacy_terms')->default(false);
            }

            if (!Schema::hasColumn('users', 'can_see_post')) {
                $table->enum('can_see_post', CanSeePostType::getValues())
                    ->default('all');
            }

            if (!Schema::hasColumn('users', 'can_see_comment')) {
                $table->enum('can_see_comment', CanSeeCommentType::getValues())
                    ->default('all');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['read_privacy_terms', 'can_see_post', 'can_see_comment']);
        });
    }
};
