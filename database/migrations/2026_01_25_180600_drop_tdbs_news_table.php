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
        Schema::dropIfExists('tdbs_news');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tdbs_news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('タイトル');
            $table->text('description')->comment('説明');
            $table->unsignedBigInteger('creator_id')->nullable()->index('news_creator_id_foreign')->comment('作成者ID');
            $table->date('publish_date')->nullable()->comment('公開日');
            $table->tinyInteger('publish_flag')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraint
            $table->foreign('creator_id')->references('id')->on('tdbs_accounts')->onUpdate('restrict')->onDelete('restrict');
        });
    }
};
