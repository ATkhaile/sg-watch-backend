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
        Schema::table('custom_links', function (Blueprint $table) {
            $table->foreignId('action_master_id')
                ->after('redirect_url')
                ->nullable()
                ->constrained('action_masters');

            $table->text('message')->nullable()->after('action_master_id');

            $table->unsignedBigInteger('scenario_id')->nullable()->after('message');
            $table->unsignedBigInteger('scenario_step_id')->nullable()->after('scenario_id');
            $table->unsignedBigInteger('tag_id')->nullable()->after('scenario_step_id');

            $table->foreign('scenario_id')->references('id')->on('scenarios');
            $table->foreign('scenario_step_id')->references('id')->on('scenario_steps');
            $table->foreign('tag_id')->references('id')->on('tags');

            $table->dropColumn('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_links', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['scenario_step_id']);
            $table->dropForeign(['scenario_id']);
            $table->dropForeign(['action_master_id']);

            $table->dropColumn([
                'action_master_id',
                'message',
                'scenario_id',
                'scenario_step_id',
                'tag_id',
            ]);
        });
    }
};
