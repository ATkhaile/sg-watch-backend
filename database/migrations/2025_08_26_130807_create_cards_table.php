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
        Schema::create('tdbs_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id')->index('cards_account_id_foreign')->comment('アカウントID');
            $table->string('card_id');
            $table->string('card_holder_name')->nullable()->comment('カード名義人の名前');
            $table->string('brand');
            $table->string('last4');
            $table->string('exp_month');
            $table->string('exp_year');
            $table->tinyInteger('status')->nullable()->comment('支払い情報ステータス');
            $table->tinyInteger('default_flag')->default(1);
            $table->date('last_using_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraint
            $table->foreign('account_id')->references('id')->on('tdbs_accounts')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_cards');
    }
};
