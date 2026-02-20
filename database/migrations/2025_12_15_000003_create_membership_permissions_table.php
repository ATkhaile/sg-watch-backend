<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('membership_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membership_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            $table->unique(['membership_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_permissions');
    }
};
