<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Event ID (evt_xxx)');
            $table->string('type', 100)->comment('イベントタイプ (e.g., payment_intent.succeeded)');
            $table->string('api_version', 50)->nullable()->comment('API バージョン');
            $table->json('data')->nullable()->comment('イベントデータ');
            $table->string('request_id', 100)->nullable()->comment('リクエストID');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->boolean('pending_webhooks')->default(false)->comment('保留中のWebhook');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'type']);
            $table->index(['stripe_account_id', 'stripe_created']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_events');
    }
};
