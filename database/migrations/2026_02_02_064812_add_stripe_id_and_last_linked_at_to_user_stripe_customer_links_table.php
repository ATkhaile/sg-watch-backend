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
        Schema::table('user_stripe_customer_links', function (Blueprint $table) {
            $table->bigInteger('stripe_id')->nullable()->after('stripe_customer_id')->comment('stripe_customers.id (同期済みの場合のみ)');
            $table->timestamp('last_linked_at')->nullable()->after('is_primary')->comment('最後に紐付けを更新した日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_stripe_customer_links', function (Blueprint $table) {
            $table->dropColumn(['stripe_id', 'last_linked_at']);
        });
    }
};
