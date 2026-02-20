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
        Schema::create('my_sns', function (Blueprint $table) {
            $table->id();
            $table->ulid('user_id');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->text('service_name')->nullable()->comment('サービス名');
            $table->text('service_description')->nullable()->comment('サービスの基本的な説明');
            $table->text('usage_description')->nullable()->comment('具体的な使用方法の説明');
            $table->text('pricing_plan')->nullable()->comment('料金プラン');
            $table->text('usage_limit')->nullable()->comment('利用制限');
            $table->text('supported_env')->nullable()->comment('対応環境など');
            $table->text('faq')->nullable()->comment('よくある質問');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_sns');
    }
};
