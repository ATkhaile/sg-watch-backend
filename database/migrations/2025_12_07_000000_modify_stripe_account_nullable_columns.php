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
        Schema::table('stripe_account', function (Blueprint $table) {
            $table->string('stripe_webhook_secret')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Note: NULLデータを保持するため、NOT NULLへの変更は行わない
     */
    public function down(): void
    {
        // NULLデータが存在する可能性があるため、nullable維持
    }
};
