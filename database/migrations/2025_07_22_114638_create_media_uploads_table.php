<?php

use App\Enums\FileType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->enum('type', FileType::all());
            $table->bigInteger('file_size');
            $table->unsignedBigInteger('create_user_id');
            $table->unsignedBigInteger('media_folder_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('create_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('media_folder_id')
                ->references('id')->on('media_folders')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_uploads');
    }
};
