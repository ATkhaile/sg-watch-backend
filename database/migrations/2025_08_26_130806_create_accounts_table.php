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
        Schema::create('tdbs_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('is_admin')->default(0);
            $table->tinyInteger('account_type')->default(1);
            $table->integer('account_number')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('email', 270)->nullable();
            $table->string('email_send')->nullable();
            $table->string('line_name')->nullable();
            $table->string('last_name_kanji')->nullable();
            $table->string('first_name_kanji')->nullable();
            $table->string('last_name_kana')->nullable();
            $table->string('first_name_kana')->nullable();
            $table->string('group_name')->nullable();
            $table->string('group_name_kana')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->unsignedBigInteger('prefecture_id')->nullable()->index('accounts_prefecture_id_foreign');
            $table->string('city')->nullable();
            $table->string('street_address')->nullable();
            $table->string('building')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('line_id')->nullable();
            $table->string('line_user_id')->nullable();
            $table->boolean('verified')->default(true);
            $table->string('line_access_token')->nullable();
            $table->tinyInteger('is_account_line')->default(0);
            $table->text('memo')->nullable();
            $table->tinyInteger('is_black')->default(0);
            $table->boolean('billing_same_address_flag')->nullable()->default(false);
            $table->dateTime('last_login_at')->nullable();
            $table->rememberToken();
            $table->date('birthday')->nullable();
            $table->dateTime('last_update_payment')->nullable();
            $table->boolean('payment_status')->default(false);
            $table->unsignedBigInteger('admin_id_update_payment')->nullable();
            $table->dateTime('last_change_status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraint
            $table->foreign('prefecture_id')->references('id')->on('tdbs_prefectures')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_accounts');
    }
};
