<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('membership_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membership_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('granted_at')->nullable()->comment('When membership was granted');
            $table->timestamp('expires_at')->nullable()->comment('When membership expires (null = permanent)');
            $table->string('granted_by')->nullable()->comment('admin or plan');
            $table->unsignedBigInteger('granted_by_id')->nullable()->comment('Admin user ID or plan ID that granted this');
            $table->timestamps();

            $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['membership_id', 'user_id']);
            $table->index('user_id');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_users');
    }
};
