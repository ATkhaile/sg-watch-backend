<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('chat_group_members', function (Blueprint $table) {
            $table->enum('role', ['member', 'admin', 'owner', 'viewer'])->default('member')->change();
        });
    }

    public function down(): void
    {
        Schema::table('chat_group_members', function (Blueprint $table) {
            $table->enum('role', ['member', 'admin', 'owner'])->default('member')->change();
        });
    }
};
