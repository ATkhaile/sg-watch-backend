<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * column_payments テーブルを削除
     * Columns機能から決済機能を削除するため
     */
    public function up(): void
    {
        Schema::dropIfExists('column_payments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('column_payments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('column_id');
            $table->string('stripe_session_id')->unique();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('stripe_metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('column_id')->references('id')->on('columns')->onDelete('cascade');
            $table->index(['user_id', 'column_id']);
            $table->index('status');
        });
    }
};
